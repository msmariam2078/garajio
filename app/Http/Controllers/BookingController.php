<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Inspection;
use App\Models\GroupInspection;
use App\Models\TempInspection;
use App\Models\GroupPoint;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\BookingItems;
use App\Models\ServiceGroups;
use App\Models\CustomerTemplate;
use App\Models\ClientDetail;
use App\Models\ServiceMaster;
use App\Models\ServicePartadjustMent;
use App\Models\ServicePart;
use App\Models\BookingQuotation;
use App\Models\Booking_comment;
use App\Models\SkillGroup;
use Carbon\Carbon;
use DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Mail;
use App\Models\Warranty_registration;
use App\Models\Warrenty_extend;
use App\Mail\QuotationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Services\FirebaseService;

class BookingController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }
    
    public function index()
    {
        $bookings = Booking::all()->map(function ($booking) {
         
            $serviceGroupIds = json_decode($booking->service_group, true);
    
        
            $skillGroupIds = json_decode($booking->skill_group, true);
    
            if (is_array($skillGroupIds) && count($skillGroupIds) > 0) {
              
                $skillGroupNames = SkillGroup::whereIn('id', $skillGroupIds)->pluck('group_name')->toArray();
                $booking->skill_group_names = implode(', ', $skillGroupNames); 
            } else {
                $booking->skill_group_names = null; 
            }
    
            return $booking;
        });
    
        
        return view('booking.index', compact('bookings'));
    }
    
    
    
    
    public function create()
    {
        $settings = settings();
         $clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('full_name', 'id');
        $technicians = User::where('parent_id', parentId())->where('type', 'technician')->get()->pluck('id', 'full_name');
        $supervisors = User::where('parent_id', parentId())->where('type', 'supervisor')->get()->pluck('id', 'full_name');
        $templates=TempInspection::all();
        $vehicles = Vehicle::get()->pluck('name', 'id');
        $servicegroups = ServiceGroups::get();
        $country = User::$country;
        $skillgroups = SkillGroup::all();
        $country_s = settings()['country'];
        $latestBookingId = Booking::latest('id')->value('id');
        $nextBookingId = $latestBookingId ? $latestBookingId + 1 : 1;
        $skillGroups = SkillGroup::with(['skill', 'vehicleMake', 'vehicleModel'])
        ->get();
                       
    $bookingReference = $settings['booking_number_prefix'] . $nextBookingId;
        return view('booking.create', compact('clients','technicians','supervisors','templates','vehicles', 'skillgroups','servicegroups', 'country', 'country_s','settings','bookingReference','skillGroups'));
    }

    public function store(Request $request)
    {
       

       
        DB::beginTransaction();
    
        try {
            // Create a new booking
            $booking = new Booking();
            $booking->reference = $request->reference;
            $booking->client = $request->customer_id;
            $booking->vehicle = $request->vehicle_id;
            $booking->service_group = json_encode($request->service_group);
            $booking->skill_group = json_encode($request->skill_group);
            $booking->booking_date = $request->bookingDate;
            $booking->booking_time = $request->bookingTime;
            $booking->requested_date = $request->requestdate;
            $booking->due_date = now();
            $booking->requested_time = $request->requesttime;
            $booking->city = $request->city;
            $booking->country = $request->country;
            $booking->service_location = $request->service_location;
            $booking->description = $request->description;
            $booking->status = "Booking";
            $booking->task_priority = $request->task_priority;
            $booking->job_type = $request->job_type;
            $booking->technician = $request->technician;
            $booking->supervisor = $request->supervisor;
            $booking->source = $request->source;
            $booking->landmark = $request->landmark;
            $booking->warrantyregisterations = $request->warrantyregisterationselectedIds;
			$booking->created_by = \Auth::id();
            $booking->save();
    
           
            $products = $request->input('products');
    
            if (!empty($request->warrantyregisterationselectedIds)) {
                $warrantyregisteration = Warranty_registration::findOrFail($request->warrantyregisterationselectedIds);
    
              
               // $warrantyregisteration->claim_count += 1;
//$warrantyregisteration->save();
    
                // Handle warranty extension only if `subrowdata` exists
                if ($request->has('subrowdata')) {
                    $subrowData = json_decode($request->subrowdata, true);
                    if($subrowData){
                    foreach ($subrowData as $subrow) {
                        Warrenty_extend::create([
                            'customer_id' => $warrantyregisteration->customer_id,
                            'product_id' => $warrantyregisteration->product_id,
                            'warranty_registration_id' => $warrantyregisteration->id,
                            'purchase_date' => $warrantyregisteration->warranty_start_date,
                            'title' => $subrow['title'],
                            'status'=>'1',
                            'extend_start_date' => date('Y-m-d', strtotime($subrow['startDate'])),
                            'extend_end_date' => date('Y-m-d', strtotime($subrow['endDate'])),
                            'warranty_registeration_id' => $warrantyregisteration->id,
                        ]);
                    }
                }
                }
            }
            
    
            // Create a quotation entry
            $quotation = new BookingQuotation();
            $quotation->customer_id = $request->customer_id;
            $quotation->booking_id = $booking->id;
            $quotation->send_date = now()->toDateString(); 
            $quotation->status = 'Confirmed';
            $quotation->due_date = now();
			$quotation->created_by = \Auth::id();
            $quotation->save();
    
            foreach ($products as $product) {
                $bookingItem = new BookingItems();
                $bookingItem->quotation_id = $quotation->id;
                $bookingItem->product_id = $product['product_id'];
                $bookingItem->item_no = $product['item_no'];
                $bookingItem->item_type = $product['item_type'];
                $bookingItem->product_name = $product['product_name'];
                $bookingItem->qty = $product['quantity'];
                $bookingItem->unit_price = $product['unit_price'];
                $bookingItem->gstprice = $product['gst'];
                $bookingItem->linetotal = $product['line_total'];
                $bookingItem->warrenty = $product['warranty'];
                $bookingItem->taxpercentage = $product['taxpercentage'];
                $bookingItem->taxamount = $product['taxamount'];
                $bookingItem->totalamount = $product['totalamount'];
                $bookingItem->uom = $product['uom'];
                $bookingItem->uom_name = $product['uom_name'];
                $bookingItem->save();
    
                $adjustment = ServicePartadjustMent::firstOrCreate(
                    ['service_part_id' => $bookingItem->product_id],
                    ['warehouse_id' =>$product['warehouse_id']],
                    ['commited' => 0] ,// Set default values
                    ['available' => 0],
                    ['unavailable' => 0],
                   ['onhand' => 0],
                );
                
                $adjustment->commited += $bookingItem->qty;
            //    $adjustment->available = $adjustment->onhand -  $bookingItem->qty;
                $adjustment->save();
                
            }
    
            // Retrieve coordinates for the service location
            $coordinates = $this->getCoordinates($request->service_location);
            $service_lat = $coordinates['lat'] ?? '090878';
            $service_lng = $coordinates['lng'] ?? '090878';
    
            // Create a new work order
            $workOrder = new WorkOrder();
            $workOrder->customer_id = $request->customer_id;
            $workOrder->vehicle = json_encode([$request->vehicle_id]);
            $workOrder->booking = json_encode([$booking->id]);
            $workOrder->service_group = json_encode($request->service_group);
            $workOrder->subject = $request->description;
            $workOrder->created_date = Carbon::now(); 
            $workOrder->service_location = $request->service_location;
            $workOrder->service_lat = $service_lat;
            $workOrder->service_lng = $service_lng;
            $workOrder->status = "Open";
            $workOrder->warranty = $request->warrantyregisterationselectedIds ?? null;
			$workOrder->created_by = \Auth::id();
            $workOrder->save();
    
            // Update booking with the workOrderId
            $booking->workorderid = $workOrder->id;
            $booking->save();
    
            // if ($request->comments) {
            //     foreach ($request->comments as $commentData) {
            //         $comment = new Booking_comment();
            //         $comment->user_id = \Auth::user()->id;
            //         $comment->booking_id = $booking->id;
            //         $comment->comment = $commentData['comment'];
            //         $comment->save();
            //     }
            // }
    
            
            DB::commit(); // Commit the transaction if all operations succeed
    
            return response()->json(array_merge(['workorder' => $workOrder->id]));

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on failure
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } 

	public function bookingEdit(Request $request)
	{

		$coordinates = $this->getCoordinates($request->service_location);
		$service_lat = $coordinates['lat'] ?? '090878';
		$service_lng = $coordinates['lng'] ?? '090878';


		try {

			$workOrder = WorkOrder::findOrFail($request->workorderid);
            $workOrder->service_group = json_encode($request->service_group);
            $workOrder->service_location = $request->service_location;
            $workOrder->service_lat = $service_lat;
            $workOrder->service_lng = $service_lng;
            $workOrder->isModified = 1; 
            $workOrder->status = $request->status;
            $workOrder->save();

			$booking = Booking::findOrFail($request->booking_id);
			$booking->booking_date = $request->bookingDate;
			$booking->booking_time = $request->bookingTime;
			$booking->requested_date = $request->requestdate;
			$booking->due_date = now(); // Update due_date only if necessary
			$booking->requested_time = $request->requesttime;
			$booking->due_date = $request->due_date;
			$booking->service_group = json_encode($request->service_group);
			$booking->skill_group = json_encode($request->skill_group);

			$booking->country = $request->country;
			$booking->city = $request->city;
			$booking->service_location = $request->service_location;		
			$booking->landmark = $request->landmark;
			$booking->description = $request->description;
			$booking->status = $request->status;

			$booking->save();
			return redirect()->back()->with('success', __('Booking edit successfully.'));

		} catch (\Exception $e) {
			return redirect()->back()->with('error', __('Booking update failed: ') . $e->getMessage());
        }

	}
    

    public function invoiceandpayment(Request $request)
    {
    // Save the booking
    $booking = new Booking();
    $booking->reference = $request->reference;
    $booking->client = $request->customer_id;
    $booking->vehicle = $request->vehicle_id;
    $booking->service_group = json_encode($request->service_group);
    $booking->requested_date = $request->booking_date;
    $booking->scheduled_date = $request->due_date;
    $booking->scheduled_time = $request->scheduled_time;
    $booking->city = $request->city;
    $booking->country = $request->country;
    $booking->service_location = $request->service_location;
    $booking->description = $request->description;
    $booking->status = $request->status;
	$booking->created_by = \Auth::id();
    $booking->save();

    // Process products and save booking items
    $products = $request->input('products');
    foreach ($products as $product) {
        $bookingItem = new BookingItems();
        $bookingItem->booking_id = $booking->id;
        $bookingItem->product_id = $product['product_name']; 
        $bookingItem->item_no = $product['item_no']; 
        $bookingItem->item_type = $product['item_type'];
        $bookingItem->qty = $product['quantity'];
        $bookingItem->gstprice = $product['gst'];
        $bookingItem->linetotal = $product['line_total'];
        $bookingItem->subtotal = floatval(str_replace('$', '', $product['unit_price'])) * intval($product['quantity']);
        $bookingItem->taxamount = floatval(str_replace('$', '', $product['gst']));
        $bookingItem->total = floatval(str_replace('$', '', $product['line_total']));
        $bookingItem->save();
    }

    // Get service coordinates
    $coordinates = $this->getCoordinates($request->service_location);
    if ($coordinates) {
        $service_lat = $coordinates['lat'];
        $service_lng = $coordinates['lng'];
    } else {
        return redirect()->back()->with('error', __('Could not determine coordinates for the given service location.'));
    }

    // Create the work order
    $workOrder = new WorkOrder();
    $workOrder->customer_id = $request->customer_id;
    $workOrder->vehicle = json_encode([$request->vehicle_id]); 
    $workOrder->booking = json_encode([$booking->id]); 
    $workOrder->service_group = json_encode($request->service_group);
    $workOrder->subject = $request->description;
    $workOrder->created_date = $request->bookingDate;
    $workOrder->service_location = $request->service_location;
    $workOrder->service_lat = $service_lat; 
    $workOrder->service_lng = $service_lng;
    $workOrder->save();

    // Save the work order ID in the booking table
    $booking->workorderid = $workOrder->id;
    $booking->save(); // Update the booking record with the work order ID

    // Retrieve clients
    $clients = User::where('parent_id', parentId())
        ->where('type', 'client')
        ->get()
        ->pluck('first_name', 'id');

    // Fetch related entities
    $workOrder = WorkOrder::find($workOrder->id);
    $vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
    $bookingIds = json_decode($workOrder->booking, true) ?? [];
    $inspectionIds = json_decode($workOrder->inspections, true) ?? [];
    $technicianIds = json_decode($workOrder->technician, true) ?? [];
    $invoiceIds = json_decode($workOrder->invoive, true) ?? [];
    $paymentIds = json_decode($workOrder->payment, true) ?? [];

    $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
    $bookings = Booking::whereIn('id', $bookingIds)->get();
    $inspections = Inspection::whereIn('id', $inspectionIds)->get();
    $technicians = User::whereIn('id', $technicianIds)->get();
    $invoices = Invoice::whereIn('id', $invoiceIds)->get();
    $payments = Payment::whereIn('id', $paymentIds)->get();
    $productItems = BookingItems::whereIn('booking_id', $bookingIds)
        ->get() 
        ->groupBy('booking_id');

    // Prepare data for the response
    $data = compact('clients', 'workOrder', 'vehicles', 'bookings', 'inspections', 'technicians', 'invoices', 'payments','productItems');

    // Return response with work order ID
    return response()->json(array_merge($data, ['workOrderId' => $workOrder->id]));
}

    

    private function getCoordinates($address)
    {
        $apiKey = "AIzaSyBO1Dw9T3wDRjN2RyrGLE2XTG86x46cIUc"; 

        // URL encode the input address or Plus Code
        $address = urlencode($address);
    
        // Construct the API request URL
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";
    
        try {
            // Fetch response from the API
            $response = file_get_contents($url);
            $json = json_decode($response, true);
    
            // Check if the results array is populated
            if (isset($json['results'][0])) {
                $location = $json['results'][0]['geometry']['location'];
    
                return [
                    'lat' => $location['lat'],
                    'lng' => $location['lng']
                ];
            }
    
            // Return null if no results were found
            return null;
        } catch (\Exception $e) {
            // Handle exceptions, such as network errors
            return null;
        }
    }
    public function getAllocateTechnician(Request $request, $id)
	{
		//$workOrder = WorkOrder::findOrFail($id);
		$checkinCheckout = $request->has('checkin_checkout') ? (int) $request->checkin_checkout : null;
		$skillGroup = $request->input('skill_group');

		$today = now()->format('Y-m-d');
		$todayDay = now()->format('l');

		// Get work order service coordinates
		$serviceLat = $workOrder->service_lat;
		$serviceLng = $workOrder->service_lng;

		$techniciansQuery = User::where('type', 'technician');

		// Filter technicians based on check-in/check-out status
		if ($checkinCheckout === 1) {
			$techniciansQuery->join('technician_work_hours', 'users.id', '=', 'technician_work_hours.technician_id')
				->where('technician_work_hours.status', 1) // Ensuring status is 1 for online technicians
				->where('technician_work_hours.workingday', $todayDay)
				->whereDate('technician_work_hours.start_date', $today);
		} elseif ($checkinCheckout === 0) {
			$techniciansQuery->join('technician_work_hours', 'users.id', '=', 'technician_work_hours.technician_id')
				->where('technician_work_hours.status', 0)
				->where('technician_work_hours.workingday', $todayDay)
				->whereDate('technician_work_hours.start_date', $today);
		}

		// Handle all technicians if checkin_checkout is 2 (no filtering by status)
		if ($checkinCheckout === 2) {
			$techniciansQuery->leftJoin('technician_locations', 'users.id', '=', 'technician_locations.user_id')
				->select(
					'users.*',
					'technician_locations.lat as technician_lat',
					'technician_locations.long as technician_lng'
				);
		} else {
			// Join technician_locations table when status is 1 (online technicians)
			$techniciansQuery->leftJoin('technician_locations', 'users.id', '=', 'technician_locations.user_id')
				->select(
					'users.*',
					'technician_work_hours.status as onlinestatus', // Alias the status column to 'onlinestatus'
					'technician_work_hours.technician_id',
					'technician_locations.lat as technician_lat',
					'technician_locations.long as technician_lng'
				);
		}

		// Filter by skill group if provided
		if (!empty($skillGroup)) {
			$techniciansQuery->whereRaw("FIND_IN_SET(?, users.skills)", [$skillGroup]);
		}

		// Retrieve technicians
		$technicians = $techniciansQuery->distinct()->get();

		// Filter and calculate distance only for technicians who have onlinestatus 1 and valid location data
		foreach ($technicians as $technician) {
			// Ensure you are using the alias 'onlinestatus' to check technician's status
			$distance = $this->calculateDistance(
				$technician->technician_lat,
				$technician->technician_lng,
				$serviceLat,
				$serviceLng
			);
			$technician->distance = $distance;
			// Add the distance to the technician object
		}

		return view('workorder.gettechnician', compact('technicians', 'todayDay', 'today'))->render();
	}
    
    public function start(Request $request)
    {
    // dd($request->all());
      
        DB::beginTransaction();
    
        try {
            // Create a new booking
            $booking = new Booking();
            $booking->reference = $request->reference;
            $booking->client = $request->customer_id;
            $booking->vehicle = $request->vehicle_id;
            $booking->service_group = json_encode($request->service_group);
            $booking->skill_group = json_encode($request->skill_group);
            $booking->booking_date = $request->bookingDate;
            $booking->booking_time = $request->bookingTime;
            $booking->requested_date = $request->requestdate;
            $booking->due_date = now();
            $booking->requested_time = $request->requesttime;
            $booking->city = $request->city;
            $booking->country = $request->country;
            $booking->service_location = $request->service_location;
           	$booking->description = $request->description;
            $booking->status = 'Booking';
            $booking->task_priority = $request->task_priority;
            $booking->job_type = $request->job_type;
            $booking->technician = $request->technician;
            $booking->supervisor = $request->supervisor;
            $booking->source = $request->source;
            $booking->landmark = $request->landmark;
            $booking->warrantyregisterations = $request->warrantyregisterationselectedIds;
			$booking->created_by = \Auth::id();
            $booking->save();
    
            // Save products associated with the booking
            $products = $request->input('products');
    
            if (!empty($request->warrantyregisterationselectedIds)) {
              
               $warrantyregisteration = Warranty_registration::findOrFail($request->warrantyregisterationselectedIds);
               
            //dd($warrantyregisteration);
                // Increment claim_count by 1
              //  $warrantyregisteration->claim_count += 1;
             //   $warrantyregisteration->save();
            
                // Only process subrowdata if it's not null
                if (!empty($request->subrowdata)) {
                    $subrowData = json_decode($request->subrowdata, true);
                 // dd($subrowData);
             
                    foreach ($subrowData as $subrow) {
                        Warrenty_extend::create([
                            'customer_id' => $warrantyregisteration->customer_id,
                            'product_id' => $warrantyregisteration->product_id,
                            'warranty_registration_id' => $warrantyregisteration->id,
                            'purchase_date' => $warrantyregisteration->warranty_start_date,
                            'title' => $subrow['title'],
                            'status'=>'1',
                            'extend_start_date' => date('Y-m-d', strtotime($subrow['startDate'])),
                            'extend_end_date' => date('Y-m-d', strtotime($subrow['endDate'])),
                            'warranty_registeration_id' => $warrantyregisteration->id,
                        ]);
                    }
                }
            }
            
            
    
            // Create a quotation entry
            $quotation = new BookingQuotation();
            $quotation->customer_id = $request->customer_id;
            $quotation->booking_id = $booking->id;
            $quotation->send_date = now()->toDateString(); 
            $quotation->status = 'Pending';
            $quotation->due_date = now();
			$quotation->created_by = \Auth::id();
            $quotation->save();
    
            foreach ($products as $product) {
                $bookingItem = new BookingItems();
                $bookingItem->quotation_id = $quotation->id;
                $bookingItem->product_id = $product['product_id'];
                $bookingItem->item_no = $product['item_no']; 
                $bookingItem->item_type = $product['item_type'];
                $bookingItem->product_name = $product['product_name'];
                $bookingItem->qty = $product['quantity'];
                $bookingItem->gstprice = $product['gst'];
                $bookingItem->unit_price = number_format($product['unit_price'],3);
                $bookingItem->linetotal = number_format($product['line_total'],3);
                $bookingItem->warrenty = $product['warranty'];
               // $bookingItem->location = $product['warehouse_id'];
                $bookingItem->taxpercentage = $product['taxpercentage'];
                $bookingItem->taxamount = number_format($product['taxamount'],3);
                $bookingItem->totalamount = number_format($product['totalamount'],3);
                $bookingItem->uom = $product['uom'];
                $bookingItem->uom_name = $product['uom_name'];
                $bookingItem->save();
    
                $adjustment = ServicePartadjustMent::firstOrCreate(
                    ['service_part_id' => $bookingItem->product_id],
                      ['warehouse_id' =>$product['warehouse_id']],
                    ['commited' => 0] ,// Set default values
                    ['available' => 0],
                      ['unavailable' => 0],
                       ['onhand' => 0],

                );
                $adjustment->commited += $bookingItem->qty;
              // $adjustment->available = $adjustment->onhand -  $bookingItem->qty;
                $adjustment->save();
                
            }
    
            // Retrieve coordinates for the service location
            $coordinates = $this->getCoordinates($request->service_location);
            $service_lat = $coordinates['lat'] ?? '090878';
            $service_lng = $coordinates['lng'] ?? '090878'; 
            if($request->template)
            {

            $inspection = new Inspection ();
            $inspection->booking = $booking->id;
            $inspection->equipment = $request->vehicle_id;
            $inspection->customer = $request->customer_id;
            $inspection->machine_hours = $request->machine_hours;
            $inspection->templates = $request->template['id'];
            $inspection->status = 'open';
            $inspection->groups = $request->template['groups'] ? $request->template['groups']: null;
            $inspection->save();
            $booking->status='Inspection';
            $booking->save();
            if(count($request->points)>0)
            { 
              //  dd($request->points);
                 $points=$request->points;
                
                 $filteredArray = array_filter($points, function($value) {
                       // Keep element if it's NOT an empty array
                       return ! (is_array($value) && empty($value));
                                  });
                
               //dd($filteredArray);
             foreach($filteredArray as $key =>$p)
            {
             // dd($p['point_id']);
             $point=new GroupPoint();
             $point->inspection_id=$inspection->id;
             $point->point_id=$p['point_id']; 
             $point->point_name=$p['point_name'];
             $point->group_id=$p['point_group_id'];
             $point->comment=$p['point_comment']; 
             $point->completed=$p['point_completed']; 
             $point->urgent=$p['point_urgent']; 
             $point->soon=$p['point_fixed']; 
             $point->condition=$p['point_condition']; 
             $point->maintenance_required=$p['point_maintenance']; 
             //$point->red=$p['point_red']; 
         //    $point->yellow=$p['point_yellow']; 
           //  $point->green=$p['point_green'];
             $point->save();
            }
            }
           

            }

    
            // Create a new work order
            $workOrder = new WorkOrder();
            $workOrder->warranty=$request->warrantyregisterationselectedIds ?? null ;
            $workOrder->customer_id = $request->customer_id;
            $workOrder->vehicle = json_encode([$request->vehicle_id]);
            $workOrder->booking = json_encode([$booking->id]);
            $workOrder->service_group = json_encode($request->service_group);
            $workOrder->subject = $request->description;
            $workOrder->created_date = Carbon::now(); 
            $workOrder->service_location = $request->service_location;
            $workOrder->service_lat = $service_lat;
            $workOrder->service_lng = $service_lng;
            $workOrder->status = "Open";
			$workOrder->created_by = \Auth::id();
            $workOrder->save();
           // dd($workOrder);
            // Update booking with the workOrderId
            $booking->workorderid = $workOrder->id;
            $booking->save();
    
            if ($request->comments) {
                foreach ($request->comments as $commentData) {
                    $comment = new Booking_comment();
                    $comment->user_id = \Auth::user()->id;
                    $comment->booking_id = $booking->id;
                    $comment->comment = $commentData['comment'];
                    $comment->save();
                }
            }
    
            DB::commit(); // Commit the transaction if all operations succeed
    
            return response()->json(array_merge(['booking_id' => $booking->id,'workorder'=>$workOrder->id]));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on failure
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } 
    

    public function bookingalongwithquotation(Request $request)
    {
        DB::beginTransaction();
    
        try {
            // Create a new booking
            $booking = new Booking();
            $booking->reference = $request->reference;
            $booking->client = $request->customer_id;
            $booking->vehicle = $request->vehicle_id;
            
            $booking->service_group = json_encode($request->service_group);
            $booking->requested_date = $request->bookingDate;
            $booking->scheduled_date = $request->due_date;
            $booking->scheduled_time = $request->scheduled_time;
            $booking->city = $request->city;
            $booking->country = $request->country;
            $booking->service_location = $request->service_location;
            $booking->description = $request->description;
            $booking->status = $request->status;
            $booking->save();
    
            // Save products associated with the booking
            $products = $request->input('products');
        
            foreach ($products as $product) {
                $bookingItem = new BookingItems();
                $bookingItem->booking_id = $booking->id;
                $bookingItem->product_id = $product['product_id'];
                $bookingItem->item_no = $product['item_no'];
                $bookingItem->item_type = $product['item_type']; 
                $bookingItem->product_name = $product['product_name'];
                $bookingItem->qty = $product['quantity'];
                $bookingItem->gstprice = $product['gst'];
                $bookingItem->linetotal = $product['line_total'];
                $bookingItem->warrenty = $product['warranty'];
                $bookingItem->save();
            }
    
            // Retrieve coordinates for the service location
            $coordinates = $this->getCoordinates($request->service_location);
            $service_lat = $coordinates['lat'] ?? '090878';
            $service_lng = $coordinates['lng'] ?? '090878';
    
            // Create a new work order
            $workOrder = new WorkOrder();
            $workOrder->customer_id = $request->customer_id;
            $workOrder->vehicle = json_encode([$request->vehicle_id]);
            $workOrder->booking = json_encode([$booking->id]);
            $workOrder->service_group = json_encode($request->service_group);
            $workOrder->subject = $request->description;
            $workOrder->created_date = $request->bookingDate;
            $workOrder->service_location = $request->service_location;
            $workOrder->service_lat = $service_lat;
            $workOrder->service_lng = $service_lng;
            $workOrder->save();
    
            // Update booking with the workOrderId
            $booking->workorderid = $workOrder->id;
            $booking->save();
    
            // Prepare response data
            $clients = User::where('parent_id', parentId())
                ->where('type', 'client')
                ->get()
                ->pluck('first_name', 'id');
    
            $workOrder = WorkOrder::find($workOrder->id);
            $vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
            $bookingIds = json_decode($workOrder->booking, true) ?? [];
            $inspectionIds = json_decode($workOrder->inspections, true) ?? [];
            $technicianIds = json_decode($workOrder->technician, true) ?? [];
            $invoiceIds = json_decode($workOrder->invoive, true) ?? [];
            $paymentIds = json_decode($workOrder->payment, true) ?? [];
    
            $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
            $bookings = Booking::whereIn('id', $bookingIds)->get();
            $inspections = Inspection::whereIn('id', $inspectionIds)->get();
            $technicians = User::whereIn('id', $technicianIds)->get();
            $invoices = Invoice::whereIn('id', $invoiceIds)->get();
            $payments = Payment::whereIn('id', $paymentIds)->get();
            $productItems = BookingItems::whereIn('booking_id', $bookingIds)
                ->get()
                ->groupBy('booking_id');
    
            $data = compact('clients', 'workOrder', 'vehicles', 'bookings', 'inspections', 'technicians', 'invoices', 'payments', 'productItems');
    
            DB::commit(); // Commit the transaction if all operations succeed
    
            return response()->json(array_merge($data, ['booking_id' => $booking->id]));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on failure
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function edit($id)
    {
        $settings = settings();
        $vehicles = Vehicle::get();
        $bookings = Booking::findOrFail($id);
         $inspection = Inspection::where('booking',$bookings->id)->whereNot('status','cancelled')->first();
        $groups = $inspection ? GroupINspection::whereIn('id',json_decode($inspection->groups))->pluCk('code')->toArray() : [];
        
        
        $servicegroups = ServiceGroups::get();
        $skillGroups = SkillGroup::get();
        $vehicles = Vehicle::with('vehicle_models')->findOrFail($bookings->vehicle);
        
        $quotations = DB::table('booking_quotations')
            ->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
            ->where('booking_quotations.booking_id', $id)
            ->select(
                'booking_quotations.id as quotation_id',
                'booking_quotations.send_date',
                'booking_quotations.status',
                'booking_items.product_id',
                'booking_items.item_no',
                'booking_items.item_type',
                'booking_items.product_name',
                'booking_items.unit_price',
                'booking_items.qty',
                'booking_items.gstprice',
                'booking_items.linetotal',
                'booking_items.warrenty',
                'booking_items.location',
                'booking_items.taxamount',
                'booking_items.totalamount',
                'booking_items.uom',
                'booking_items.uom_name',
                'booking_items.taxpercentage'
            )
            ->get()
            ->groupBy('quotation_id');
        
       
      
  
      
        $clientDetails = User::with('clients')->findOrFail($bookings->client);
        
     
        $bookingid = $id;
        $bookingsworkorderid = json_decode($bookings->workorderid);
        
       
        $service_group = json_decode($bookings->service_group, true) ?? []; 

        $skill_group = json_decode($bookings->skill_group, true) ?? []; 
      
        $bookingReference = $settings['booking_number_prefix'] . $bookingid;
        $country = User::$country;
        $country_s = settings()['country'];
        
        $warrantyRegistrations = [];
        if ($bookings->warrantyregisterations) {
            $warrantyRegistrations = Warranty_registration::with('product')
                ->where('id', $bookings->warrantyregisterations)
                ->get();
        }
    
       
        return view('booking.edit', compact(
            'bookings', 
            'clientDetails',
            'inspection',
            'groups', 
            'vehicles', 
            'servicegroups', 
            'service_group',
            'bookingReference', 
            'country', 
            'country_s', 
            'bookingsworkorderid',
            'quotations',
            'skillGroups'
        ));
    }
    
    



    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'client' => 'required|string|max:255',
                'vehicle' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'requested_time' => 'required|string|max:255',
                'service_group' => 'required|string|max:255',
                'scheduled_date' => 'required|date',
                'scheduled_time' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'service_location' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $booking = Booking::findOrFail($id);
        $booking->client = $request->client;
        $booking->vehicle = $request->vehicle;
        $booking->service_group = $request->service_group;
        $booking->requested_date = $request->requested_date;
        $booking->requested_time = $request->requested_time;
        $booking->scheduled_date = $request->scheduled_date;
        $booking->scheduled_time = $request->scheduled_time;
        $booking->city = $request->city;
        $booking->country = $request->country;
        $booking->service_location = $request->service_location;
        $booking->description = $request->description;
        $booking->status = $request->status;
        $booking->serviceId = $request->serviceId;
        $booking->save();

        return redirect()->back()->with('success', __('Bookings successfully updated.'));
    }


    public function destroy($id)
    {
        $bookings = Booking::find($id);
        $bookings->delete();
        return redirect()->back()->with('success', 'Note successfully deleted.');
    }



	public function show($id)
    {
        $settings = settings();
        $vehicles = Vehicle::get();
        $bookings = Booking::findOrFail($id);
        
        $servicegroups = ServiceGroups::get();
        $skillGroups = SkillGroup::get();
        $vehicles = Vehicle::with('vehicle_models')->findOrFail($bookings->vehicle);
        
        $quotations = DB::table('booking_quotations')
            ->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
            ->where('booking_quotations.booking_id', $id)
            ->select(
                'booking_quotations.id as quotation_id',
                'booking_quotations.send_date',
                'booking_quotations.status',
                'booking_items.product_id',
                'booking_items.item_no',
                'booking_items.item_type',
                'booking_items.product_name',
                'booking_items.unit_price',
                'booking_items.qty',
                'booking_items.gstprice',
                'booking_items.linetotal',
                'booking_items.warrenty',
                'booking_items.location',
                'booking_items.taxamount',
                'booking_items.totalamount',
                'booking_items.uom',
                'booking_items.uom_name',
                'booking_items.taxpercentage'
            )
            ->get()
            ->groupBy('quotation_id');
        
       
      
  
      
        $clientDetails = User::with('clients')->findOrFail($bookings->client);
        
     
        $bookingid = $id;
        $bookingsworkorderid = json_decode($bookings->workorderid);
        
       
        $service_group = json_decode($bookings->service_group, true) ?? []; 

        $skill_group = json_decode($bookings->skill_group, true) ?? []; 
      
        $bookingReference = $settings['booking_number_prefix'] . $bookingid;
        $country = User::$country;
        $country_s = settings()['country'];
        
        $warrantyRegistrations = [];
        if ($bookings->warrantyregisterations) {
            $warrantyRegistrations = Warranty_registration::with('product')
                ->where('id', $bookings->warrantyregisterations)
                ->get();
        }
    
       
        return view('booking.show', compact(
            'bookings', 
            'clientDetails', 
            'vehicles', 
            'servicegroups', 
            'service_group',
            'bookingReference', 
            'country', 
            'country_s', 
            'bookingsworkorderid',
            'quotations',
            'skillGroups'
        ));
    }

    public function getVehicleByClient(Request $request)
    {
       
        $clientId = $request->input('client_id');
    //dd($clientId);
        try {
            $vehicles = Vehicle::with(['vehicle_makes', 'vehicle_models'])
                ->where('client', $clientId)
                ->get()
              
                ->map(function ($vehicle) {
                    return [
                        'id' => $vehicle->id,
                        'rego' => $vehicle->rego,
                        'name' => $vehicle->name,
                        'make_name' => $vehicle->vehicle_makes->make_name ?? null,
                        'model_name' => $vehicle->vehicle_models->model_name ?? null,
                        'model_series' => $vehicle->model_series,
                        'engine_number' => $vehicle->engine_number,
                    ];
                });
    
            return response()->json($vehicles, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    
    

    public function removeservice($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->serviceId = '0';
        $booking->save();
        return redirect()->back()->with('success', __('Service Remove successfully.'));
    }

    public function bookinginvoice($id)
    {




        $clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('first_name', 'id');
        $workOrder = WorkOrder::with('client')->find($id);
    
        $vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
        $bookingIds = json_decode($workOrder->booking, true) ?? [];
        
        $bookings = Booking::where('id',$bookingIds)->first();
      
      

        if ($workOrder->status !== "Completed" && $workOrder->allocation_status == 0) {
            return redirect()->back()->with('error', 'Please complete the work order first.');
        }
      

        $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();

        // $bookingitems
    
        // $bookingitems = BookingItems::where('booking_id', $bookingIds)->get();

        $clientDetails = $workOrder->client;

        $quotations = DB::table('booking_quotations')
            ->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
            ->where('booking_quotations.booking_id', $bookings->id)
            ->where('booking_quotations.status', 'confirmed')
            ->select(
                'booking_quotations.id as quotation_id',
                    'booking_quotations.send_date',
                    'booking_quotations.status',
                    'booking_items.product_id',
                    'booking_items.item_no',
                    'booking_items.item_type',
                    'booking_items.product_name',
                    'booking_items.unit_price',
                    'booking_items.qty',
                    'booking_items.gstprice',
                    'booking_items.linetotal',
                    'booking_items.warrenty',
                    'booking_items.location',
                    'booking_items.taxamount',
                    'booking_items.totalamount',
                    'booking_items.uom',
                    'booking_items.taxpercentage'
            )
            ->get()
            ->groupBy('quotation_id'); 
        $totalLineTotal = $quotations->flatten()->sum('linetotal'); 
        
        $settings = settings();

        $latestBookingId = Invoice::latest('id')->value('id');
        $nextBookingId = $latestBookingId ? $latestBookingId + 1 : 1;
    
        // Create the full booking reference
        $bookingReference = $settings['invoice_number_prefix'] . $nextBookingId;
    
      
        return view('booking.invoice', compact('clients', 'workOrder', 'vehicles', 'clientDetails','settings','bookingReference','bookings','quotations','totalLineTotal'));
    }

    public function bookinginvoicetechnician($id)
    {
        $clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('first_name', 'id');
        $workOrder = WorkOrder::with('client')->find($id);
    
        $vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
        $bookingIds = json_decode($workOrder->booking, true) ?? [];
        
        $bookings = Booking::where('id',$bookingIds)->first();
      
      

      

        $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();

        // $bookingitems
    
        // $bookingitems = BookingItems::where('booking_id', $bookingIds)->get();

        $clientDetails = $workOrder->client;

        $quotations = DB::table('booking_quotations')
            ->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
            ->where('booking_quotations.booking_id', $bookings->id)
            ->where('booking_quotations.status', 'confirmed')
            ->select(
            'booking_quotations.id as quotation_id',
                'booking_quotations.send_date',
                'booking_quotations.status',
                'booking_items.product_id',
                'booking_items.item_no',
                'booking_items.item_type',
                'booking_items.product_name',
                'booking_items.unit_price',
                'booking_items.qty',
                'booking_items.gstprice',
                'booking_items.linetotal',
                'booking_items.warrenty',
                'booking_items.location',
                'booking_items.taxamount',
                'booking_items.totalamount',
                'booking_items.uom',
                'booking_items.taxpercentage'
        )
            ->get()
            ->groupBy('quotation_id'); 
        $totalLineTotal = $quotations->flatten()->sum('linetotal'); 
        
        $settings = settings();

        $latestBookingId = Invoice::latest('id')->value('id');
        $nextBookingId = $latestBookingId ? $latestBookingId + 1 : 1;
    
        // Create the full booking reference
        $bookingReference = $settings['invoice_number_prefix'] . $nextBookingId;
    
      
        return view('techdashboard.makeinvoice', compact('clients', 'workOrder', 'vehicles', 'clientDetails','settings','bookingReference','bookings','quotations','totalLineTotal'));
    }

    public function paymentinvoice($id)
{
    
    $clients = User::where('parent_id', parentId())
        ->where('type', 'client')
        ->get()
        ->pluck('first_name', 'id');

  
    $workOrder = WorkOrder::find($id);
    if (!$workOrder) {
        return redirect()->back()->with('error', 'Work order not found.');
    }


    $invoiceIds = json_decode($workOrder->invoice, true) ?? [];
    
   
    $firstInvoiceId = $invoiceIds[0] ?? null;
   
 
    $invoice = $firstInvoiceId ? Invoice::find($firstInvoiceId) : null;
    if (!$invoice) {
        return redirect()->back()->with('error', 'Invoice not found.');
    }

   
    $vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
    $bookingIds = json_decode($workOrder->booking, true) ?? [];

 
    $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();

   
    $clientDetails = $workOrder->client;

   
    $settings = settings();

   
    return view('booking.payment', compact(
        'clients',
        'workOrder',
        'vehicles',
        'clientDetails',
        'settings',
        'invoice'
    ));
}

public function paymentinvoicetechnician($id)
{
    
    $clients = User::where('parent_id', parentId())
        ->where('type', 'client')
        ->get()
        ->pluck('first_name', 'id');

  
    $workOrder = WorkOrder::find($id);
    if (!$workOrder) {
        return redirect()->back()->with('error', 'Work order not found.');
    }


    $invoiceIds = json_decode($workOrder->invoice, true) ?? [];
    
   
    $firstInvoiceId = $invoiceIds[0] ?? null;

 
    $invoice = $firstInvoiceId ? Invoice::find($firstInvoiceId) : null;
    if (!$invoice) {
        return redirect()->back()->with('error', 'Invoice not found.');
    }

   
    $vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
    $bookingIds = json_decode($workOrder->booking, true) ?? [];

 
    $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();

   
    $clientDetails = $workOrder->client;

   
    $settings = settings();

   
    return view('techdashboard.makepayment', compact(
        'clients',
        'workOrder',
        'vehicles',
        'clientDetails',
        'settings',
        'invoice'
    ));
}

    
    public function generatequotation($id)
    {
       
       
    
      
        
    
       $quotation= BookingQuotation::findOrFail($id);
        $booking = Booking::findOrFail($quotation->booking_id);
        $client = User::with('clients')->findOrFail($booking->client);
       
    
      
        $vehicle = Vehicle::with('vehicle_models')->findOrFail($booking->vehicle);
    
        $products = BookingItems::where('quotation_id', $quotation->id)->get();
    
      
        return view('invoicetemplate.invoice', compact('booking', 'client', 'vehicle', 'products','quotation'));
    }
    

    public function generateInvoicePDF($bookingId)
    {  
        // Fetch the necessary data
        $booking = Booking::findOrFail($bookingId);
        $client = User::findOrFail($booking->client);
        $vehicle = Vehicle::with('vehicle_models')->findOrFail($booking->vehicle);
        $quotationbooking=BookingQuotation::where('booking_id',$bookingId)->first();
        $products = $quotationbooking->items ?? [];
      //  dd($quotationbooking);
        // Prepare the HTML content for the PDF
     
     
  $html='<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Invoice Quotation</title>

    <!-- Favicon -->
    <link rel="icon" href="./images/favicon.png" type="image/x-icon" />

    <!-- Invoice styling -->
    <style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.5;
    }


    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 24px;
        margin: 0;
    }

    .header p {
        margin: 5px 0;
        font-size: 14px;
    }

    /* Flexbox for Customer and Vehicle Details */
    .details-section {
    
        margin-bottom: 20px;
    }

    .details-section .details-box {
        width: 40%;
        padding: 10px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    .details-box p {
        margin: 5px 0;
        font-size: 14px;
    }

    /* Table */
    .quotation-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .quotation-table th,
    .quotation-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
        font-size: 14px;
    }

    .quotation-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    .quotation-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .total-section p,
    .notes-section ul li {
        font-size: 14px;
    }

    .footer {
        text-align: left;
        font-size: 14px;
        margin-top: 20px;
    }

    .footer p {
        margin: 5px 0;
    }
    </style>
</head>


<body>
    <div class="container">

        <header class="header">
        <img src=assets/img/faces/logo.jpg  style="float:left;width:120px;height:40px;">
            <div>
            <div style="text-align:right;">Created::'. optional($booking->requested_date)->format('F j, Y') .'</div>
            <div style="text-align:right;">Quotation No :#QOT'.$booking->id.'</div>
           
             <div style="text-align:right;">Due: ' . optional($booking->scheduled_date)->format('F j, Y') . '</div>
               </div>
        </header>


        <section class="details-section" style="position:relative;height:20%">

            <div class="details-box" style="position:absolute;left:0">
            <p><strong>Customer:</strong>'. $client->full_name.'</p>
                <p><strong>Addresss:</strong> '.$client?->clients?->service_address
          
                               .' <br>'. $client?->clients?->service_city.
                                ','. $client?->clients?->service_state.
                                ',' .$client?->clients?->service_country.
                                  ',' . $client?->clients?->service_zip_code.'
                                </p>
                <p><strong>Tel No:</strong> +'. preg_replace('/[^0-9]/', '',  $client?->ccm ). $client?->phone_number.'</p>
                <p><strong>Email:</strong>  '.$client?->email.'</p>
                
            </div>


            <div class="details-box" style="position:absolute;right:0">';
            
        
                $html.='<p><strong>Registation No:</strong>'.  $vehicle->rego  .'.</p>
                <p><strong>Make::</strong> '.  $vehicle->vehicle_makes?->make_name  .'</p>
                <p><strong>Model:</strong>   '. $vehicle->vehicle_models?->model_name .'</p>
                 <p><strong>Year:</strong>   '. $vehicle->model_series .'</p>
              
           
            </div>
        </section>




       
   <section >

        <table class="quotation-table">
            <thead>
            <tr>
										<th scope="col">Item no</th>
										<th scope="col">Description</th>
										<th scope="col">Warranty</th>
										<th scope="col">Warranty Period</th>
										<th scope="col">Qnty</th>
										<th scope="col">Unit price</th>
										<th scope="col">Discount %</th>
										<th scope="col">Sub total</th>
										<th scope="col">Tax %</th>
										<th scope="col">Tax amount</th>
										<th scope="col">Total amount</th>
									</tr>
            </thead>
            <tbody>';
       
            $subtotal=0; 
            $discount=0;
            $tax = 0;
                
           foreach ($products as $product){
            
                                                    $html.='<tr>
                                                       
                                                        <td> '.$product->product_id.'</td>
                                                        <td> '.$product->product_name .'</td>
                                                        <td> '.$product->warrenty.' </td>
                                                        <td>'. $product->warrenty.' </td>
                                                        <td> '.$product->qty.' </td>
                                                        <td> '.$product->unit_price .'</td>
                                                      
                                                        <td> '.$product->gstprice .'</td>
                                                        <td> '.$product->linetotal .'</td>
                                                       
                                                        <td> '.$product->taxpercentage .'</td>
                                                        <td>' .$product->taxamount .'</td>
                                                        <td>' .$product->totalamount .'</td>
                                                    </tr>';
                                                   $dis_int= (int) filter_var($product->gstprice, FILTER_SANITIZE_NUMBER_INT) ?? 0;
                                                   $unit_price=(doubleval($product->unit_price )?? 0) *(int)($product->qty ?? 1);
                                                   $discount= $discount + ($dis_int/100)* $unit_price ; 
                                               
                                                   $subtotal= $subtotal +$unit_price ;
                                                   
                                                   $tax= $tax + $product->taxamount ;
                                                  
                                                   
        }
    
        $duetotal=$subtotal +$tax;
        $html.='    </tbody>
        </table>
   </section>
        <!-- Total Section -->
        <section class="total-section text-right" style="text-align:right">
            
        
								<p>Sub total :'.$subtotal.'</p>
								<p>Tax:'.$tax.'</p>
								<p>Discount :'.$discount.'</p>
								<p>Total Due: <b>'.$duetotal.'</b></p>
								
						
        </section>


        <!-- Notes Section -->
        <section>
        <h3 style="font-weight:bold">Notes:</h3><br>
            <ol>
                <li>Supplementary will be submitted as required.</li>
                <li>Part prices are subject to change without prior notice.</li>
                <li>Parts prevailing at the time of delivery shall be changed.</li>
                <li>Delivery is subject to the availability of parts.</li>
                <li>VAT @ 5% is applicable</li>
                <li>This estimate is valid for only 7 days.</li>
            </ol>
        </section>

        <!-- Footer Section -->
        <footer class="footer">
         
            <p>Prepared By: SHAIK.SULTAN</p>
        </footer>


    </div>
</body>';
    
        // Generate PDF using Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true); 
        $options->set('isPhpEnabled', true);  
    
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml('estimation.one');
        //['quotation'=>$quotationbooking,'client'=>$client,'booking'=>$booking,'products'=>$products,'vehicle'=>$vehicle]);
    
        // Set paper size
        $dompdf->setPaper('A4', 'portrait');  
    
        // Render PDF
        $dompdf->render();
    
        // Define output path
        $pdfOutputPath = public_path('invoices/invoice_' . $bookingId . '.pdf');
    
        // Save PDF file
        file_put_contents($pdfOutputPath, $dompdf->output());
    
        // Return the file URL in the response
        return response()->json([
            'success' => true,
            'file_url' => asset('invoices/invoice_' . $bookingId . '.pdf')
        ]);
    }
    
    public function sendInvoiceEmail($quoteIdId)
    {
         
        $estimation = BookingQuotation::findOrFail($quoteIdId);
        //BookingQuotation::where('booking_id',$booking->id)->first();
        $booking =Booking::findOrFail($estimation->booking_id);     
       // $client = User::with('clients')->findOrFail($booking->client);
    
      
        $vehicle = Vehicle::with('vehicle_models')->findOrFail($booking->vehicle);
    
        $products = BookingItems::where('quotation_id', $estimation->id)->get();
        //dd($estimation);
		$user = User::findorFail($estimation->customer_id);
		$details = [
			'to' => $user->email,
			'from' => Auth::user()->email,
			'from_name' => 'Dial A Battery',
			'subject' => 'Dial A Battery - Quotation:QUT00'.$estimation->id,
			'heading' => 'Dial A Battery - Quotation:QUT00'.$estimation->id,
			'body' => 'Thank you for your interest in our vehicle service.Please find attached the quotation for your requested service The attached PDF includes full details, pricing, and terms.If you have any questions or would like to proceed, please feel free to contact us. We look forward to assisting you.',
	
            'name' => $user->full_name,
			'amount' => $estimation->items?->sum('totalamount'),
			'footer' => 'If you have any questions, feel free to contact our support team.',
		];
		Mail::send(new QuotationEmail($details, $estimation,$booking,$user,$products,$vehicle));
    
        return response()->json(['success' => true, 'message' => 'Quotation sent via email']);
    }

    public function cancelbooking($id)
    {
        $booking = Booking::findOrFail($id);
        
       
    
        return view('booking.cancel', ['bookingid' => $id]);
    }
  
public function postCancelBooking(Request $request)
{
    $request->validate([
        'booking_id' => 'required|exists:bookings,id',
        'cancellation_note' => 'required|string|max:500',
        'cancellation_reason' => 'required|string|max:255',
    ]);

    // Retrieve the booking model instance
    $booking = Booking::findOrFail($request->input('booking_id'));

    // Update the booking fields
    $booking->update([
        'status' => 'canceled',
        'notes' => $request->input('cancellation_note'),
        'reasons' => $request->input('cancellation_reason'),
    ]);

    // Delete associated quotation and booking items
    if ($booking->quotation_id) {
        Quotation::where('id', $booking->quotation_id)->delete();
        BookingItem::where('quotation_id', $booking->quotation_id)->delete();
    }

    // Delete associated work order
    if ($booking->workorderid) {
        WorkOrder::where('id', $booking->workorderid)->delete();
    }

    // Nullify workorder ID (if applicable)
    $booking->workorderid = null;
    $booking->save();

    // Redirect with success message
    return redirect()->route('booking.index')->with('success', 'Booking canceled successfully.');
}


public function softallocatetechnician(Request $request)
{
    $serviceLocation = $request->input('service_location');
    $serviceGroup = $request->input('service_group', []); 
   

    
    $serviceCoordinates = $this->getCoordinatesFromAddress($serviceLocation);

    
    $technicians = collect();

    if ($serviceCoordinates && !empty($serviceGroup)) {

     
        $technicians = User::where(function ($query) use ($serviceGroup) {
            foreach ($serviceGroup as $service) {
              
                $query->orWhereRaw('FIND_IN_SET(?, services)', [$service]);
            }
        })
        ->where('type', 'technician')
        ->whereHas('technicianlocation', function ($query) {
            $query->whereNotNull('user_id');
        })
        ->with('technicianlocation')
        ->get()
        ->map(function ($technician) use ($serviceCoordinates) {
            $latitude = $technician->technicianlocation->latitude;
            $longitude = $technician->technicianlocation->longitude;

            $technicianCoordinates = ['lat' => $latitude, 'lng' => $longitude];
            $distance = $this->calculateDistance(
                $serviceCoordinates['lat'], $serviceCoordinates['lng'],
                $technicianCoordinates['lat'], $technicianCoordinates['lng']
            );
            $technician->distance = $distance;

            return $technician;
        })
        ->sortBy('distance');
    }

   

   
    if ($technicians->isEmpty()) {
        return response()->json(['message' => 'No technicians found based on the selected criteria.']);
    }

    return response()->json(['technicians' => $technicians]);
}


    
private function getCoordinatesFromAddress($address)
{
    
    $apiKey = env('GOOGLE_MAP_KEY'); 
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".$apiKey;

    $response = file_get_contents($url);
    $response = json_decode($response, true);

    if (isset($response['results'][0])) {
        $location = $response['results'][0]['geometry']['location'];
        return [
            'lat' => $location['lat'],
            'lng' => $location['lng']
        ];
    }

    return null;
}

private function calculateDistance($lat1, $lng1, $lat2, $lng2)
{
$earthRadius = 6371; 

$latDelta = deg2rad($lat2 - $lat1);
$lngDelta = deg2rad($lng2 - $lng1);

$a = sin($latDelta / 2) * sin($latDelta / 2) +
    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
    sin($lngDelta / 2) * sin($lngDelta / 2);

$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

return $earthRadius * $c;
}
private function getCurrentLocationFromCoordinates($lat, $lng)
{
    $apiKey = env('GOOGLE_MAP_KEY');
    $client = new Client();

    try {
        $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json", [
            'query' => [
                'latlng' => "$lat,$lng",
                'key' => $apiKey
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['results'][0])) {
            return $data['results'][0]['formatted_address'];
        }

    } catch (\Exception $e) {
        \Log::error("Error fetching location: " . $e->getMessage());
        return 'Location not found';
    }

    return 'Location not found';
}


public function cancelquotation($id,$source){
    $quotationid = $id;
    $source=$source;
    return view('booking.cancelquotation',compact('quotationid','source'));
}

public function postquotationcancel(Request $request)
{
   
  

    $bookingId = $request->input('quotationid');
    $cancellationNote = $request->input('cancellation_note');

   if($request->source=='ins')
    {
        
      Inspection::where('id', $bookingId)->update([
        'status' => 'cancelled',
        'cancelMessage' => $cancellationNote,
    ]);  
       
    return redirect()->back()->with('success', 'Inspection cancelled successfully.'); 
    }else{
    $quotation= BookingQuotation::where('id', $bookingId)->first();
    $quotation->status = 'cancelled';
    $quotation->cancelMessage =$cancellationNote;
    $quotation->save();
    // ->update([
    //     'status' => 'cancelled',
    //     'cancelMessage' => $cancellationNote,
    // ]);

//    $quotation->items()->delete();
    return redirect()->back()->with('success', 'Quotation cancelled successfully.');
    }
}
public function updatebookininspection (Request $request){
   // dd($request->all());
   $inspection = Inspection::find($request->inspection_id);
   $inspection->machine_hours=$request->m_hour;
   $inspection->condition_summary = $request->condition_summary;
   $inspection->condition_comment = $request->condition_comment;
   $inspection->Save();
   $inspection->mainpoints()->delete();
    if(count($request->points)>0)
            { 
              //  dd($request->points);
                 $points=$request->points;
                
                 $filteredArray = array_filter($points, function($value) {
                       // Keep element if it's NOT an empty array
                       return ! (is_array($value) && empty($value));
                                  });
                
               //dd($filteredArray);
             foreach($filteredArray as $key =>$p)
            {
             // dd($p['point_id']);
             $point=new GroupPoint();
             $point->inspection_id=$inspection->id;
             $point->point_id=$p['point_id']; 
             $point->point_name=$p['point_name'];
             $point->group_id=$p['point_group_id'];
             $point->comment=$p['point_comment']; 
             $point->completed=$p['point_completed']; 
             $point->urgent=$p['point_urgent']; 
             $point->soon=$p['point_fixed']; 
             $point->condition=$p['point_condition']; 
             $point->maintenance_required=$p['point_maintenance'];
            // $point->red=$p['point_red']; 
          //   $point->yellow=$p['point_yellow']; 
        //     $point->green=$p['point_green'];
             $point->save();
            }
            }
            return redirect()->back()->with('success', 'Booking inspection has been updated successfully.');

}
public function storebookininspection(Request $request)
{
  //dd($request->all());
    $booking=Booking::find($request->booking_id);
    //dd($booking);
    $booking->status='Inspection';
    $booking->save();
    $template=TempInspection::find($request->template);
   // dd($template);
    $inspection = new Inspection();
    $inspection->customer = $request->customer_id;
    $inspection->booking = $request->booking_id;
    $inspection->equipment = $request->equipment;
    $inspection->templates = $request->template;
    $inspection->groups = $template->groups;
    $inspection->condition_summary = $request->condition_summary;
    $inspection->condition_comment = $request->condition_comment;
    $inspection->status = 'open';
    $inspection->save();
        if(count($request->points)>0)
            {  $points=$request->points;
                // array_shift($points);
                // array_pop($points);
               // dd($points);
             foreach($points as $key =>$p)
            {
             // dd($p['point_id']);
             $point=new GroupPoint();
             $point->inspection_id=$inspection->id;
             $point->point_id=$p['point_id']; 
             $point->point_name=$p['point_name'];
             $point->group_id=$p['point_group_id'];
             $point->comment=$p['point_comment']; 
             $point->completed=$p['point_completed']; 
             $point->urgent=$p['point_urgent']; 
             $point->soon=$p['point_fixed']; 
             $point->condition=$p['point_condition']; 
             $point->maintenance_required=$p['point_maintenance'];
             //$point->red=$p['point_red']; 
             //$point->yellow=$p['point_yellow']; 
           //  $point->green=$p['point_green'];
             $point->save();
            }
            }

  
    if($request->workorder_id)
    {
        $workOrder=WorkOrder::findOrFail($request->workorder_id);
        $workOrder->status='Start Inspection';
        $workOrder->save();
    }

  
    return redirect()->back()->with('success', 'Booking Inspection has been saved successfully.');
}

public function acceptQuotation(Request $request, $id)
{
    
    $quotation = BookingQuotation::find($id);

    if (!$quotation) {
        return redirect()->back()->with('error', 'Quotation not found.');
    }

  
    $quotation->update([
        'status' => 'accepted',
    ]);

    return redirect()->back()->with('success', 'Quotation accepted successfully.');
}

public function deletequotation($id)
{
   
    $quotation = BookingQuotation::find($id);

   
    if ($quotation) {
        
        BookingItems::where('quotation_id', $id)->delete();

     
       

     
        $quotation->delete();

      
        return redirect()->back()->with('success', 'Quotation, booking items, and booking quotation deleted successfully.');
    }

   
    return redirect()->back()->with('error', 'Quotation not found.');
}

public function confirmquotation(Request $request, $id)
{
  
    $quotation = BookingQuotation::find($id);

  
    if (!$quotation) {
        return redirect()->back()->with('error', 'Quotation not found.');
    }

   
    $booking_id = $quotation->booking_id;

  
    $booking = Booking::find($booking_id);
    $workOrder = WorkOrder::find($booking->workorderid);

   
    if ($booking) {
        $booking->status = 'Quotation';
        $booking->save();
    }

    $quotation->update([
        'status' => 'confirmed',
    ]);

    if($workOrder){
        $this->firebase->sendNotification([
            'title' => "Alert For workorder ID: ".$workOrder->id,
            'type' => 'technican_alert',
            'section' => 'quotation',
            'authId' => json_decode($workOrder->technician, true),
        ]);
    }

    return redirect()->back()->with('success', 'Quotation accepted successfully.');
}



public function storebookingquotation(Request $request)
{
   
//dd($request->all());
    if($request->booking_id)
     { $booking = Booking::findOrFail($request->booking_id);}
    if ($booking) {
       // $workorder = WorkOrder::findOrFail($request->workorder_id);
 
        $quotation = BookingQuotation::where('booking_id', $booking->id)->first();
        if($quotation)
        {
             if (!empty($request->products) && is_array($request->products)) {
            foreach($quotation->items as $item){
                $item->delete();

            }
        }
        }
 //dd($quotation);
        if (!$quotation) {

    $quotation = new B.ookingQuotation();
    $quotation->customer_id = $request->customer_id;
    $quotation->booking_id = $request->booking_id;
    $quotation->send_date = now()->toDateString();
    $quotation->due_date = Carbon::parse($request->dueDate)->addDay()->toDateString();
	$quotation->created_by = \Auth::id();
        }
    $quotation->status = 'Pending';

    $quotation->save();

  
    foreach ($request->products as $product) {
        $bookingItem = new BookingItems();
        $bookingItem->quotation_id = $quotation->id;
        $bookingItem->product_id = $product['product_id'] ?? '';
        $bookingItem->item_no = $product['item_no'];
        $bookingItem->item_type = $product['item_type'];
        $bookingItem->product_name = $product['product_name'] ?? '';
        $bookingItem->qty = $product['quantity'] ?? 0;
        $bookingItem->gstprice = $product['gst'] ?? 0;
        $bookingItem->unit_price = $product['unit_price'];
        $bookingItem->linetotal = $product['line_total'] ?? 0;
        $bookingItem->warrenty = $product['warranty'];
        $bookingItem->taxpercentage = $product['taxpercentage'];
        $bookingItem->taxamount = $product['taxamount'];
        $bookingItem->totalamount = $product['totalamount'];
        $bookingItem->uom = $product['uom'];
        $bookingItem->uom_name = $product['uom_name'];
        $bookingItem->comment = $product['comment'];
        $bookingItem->save();
    }}

  
    return redirect()->back()->with('success', 'Booking quotation has been saved successfully.');
}
 public function getbookinginspection($id)
    {   
        $bookings = Booking::findOrFail($id);
        $bookings->status = "Inspection";
        $bookings->save();
       
        $inspection = Inspection::where('booking',$bookings->id)->whereNot('status','cancelled')->first();
        $inspection_id=$inspection?->id;
        $template = $inspection?->template;
        //dd($template);
        $groups= json_decode($inspection?->groups) ?? [];
        
        //dd($groups);
        $groups= GroupInspection:: with(['mainpoints' => function ($query) use ($inspection_id) {
          $query->where('inspection_id', $inspection_id);
          }])->whereIn('id',$groups)->get();
       // dd($groups);
         $clientDetails = User::with('clients')->find($bookings?->client);
        $vehicles = Vehicle::with('vehicle_models')->find($bookings?->vehicle);
        $templates=TempInspection::all();
       // dd($inspection);
        return view('booking.inspection',compact('clientDetails','bookings','vehicles','groups','template','inspection','templates'));
    }
    
    

public function getBookingId($customerId)
{
    $booking = Booking::where('client', $customerId)->first();
    if ($booking) {
       
        $bookingQuotation = BookingQuotation::where('booking_id', $booking->id)->first();

        if ($bookingQuotation) {
           
            return response()->json(['booking_id' => $booking->reference]);
        } else {
           
            return response()->json(['booking_id' => $booking->reference]);
        }
    }
    return response()->json(['booking_id' => null], 404);
}
    
   

public function updatequotation(Request $request)
{
  // dd($request->all());
    if($request->workorder_id)
      {  $booking = Booking::where('workorderid', $request->workorder_id)->first();}
    //dd($request->booking_id);
    if($request->booking_id)
     { $booking = Booking::findOrFail($request->booking_id);}
    if ($booking) {
       // $workorder = WorkOrder::findOrFail($request->workorder_id);
 
        $quotation = BookingQuotation::where('booking_id', $booking->id)->first();
           if($quotation)
        {
             if (!empty($request->products) && is_array($request->products)) {
            foreach($quotation->items as $item){
                $item->delete();

            }
        }
        }
 //dd($quotation);
        if (!$quotation) {
            $quotation = new BookingQuotation();
            $quotation->customer_id = $booking->client;
            $quotation->booking_id =$booking->id;
            $quotation->workorder_id = $booking->workorderid;
            
            $quotation->send_date = now()->toDateString();
        }
        $quotation->status = $request->workorder_id ? 'Confirmed' : 'Started';
        $quotation->due_date = Carbon::parse($request->dueDate)->addDay()->toDateString();
       // $quotation->status = 'Confirmed';
        $quotation->save();
 
        if (!empty($request->products) && is_array($request->products)) {
            foreach($quotation->items as $item){
                $item->delete();

            }
           // $quotation->save();
            
                    foreach ($request->products as $product) {
                       // dd($product['item_type']);
       //         if ($product['product_exist'] == "0") { // Add only if product_exist == 0
	                $productData=ServicePart::find($product['product_id']);
                    
                  //  dd($productData);
                   $discount =  $productData ? ($productData->quantity * $productData->sales_price) * ($productData->des / 100) : 0;
                   $lineTotal = $productData ? ($productData->quantity * $productData->sales_price) - $discount : 0;

              
                    $taxAmount = $productData ? $lineTotal * ($productData->tax / 100) : 0;
                    $totalAmountIncTax = $lineTotal + $taxAmount;

                    $bookingItem = new BookingItems();
                    $bookingItem->quotation_id = $quotation->id;
                    $bookingItem->product_id = $product['product_id'] ?? '';
                    $bookingItem->item_no = $product['item_no'] ?? $productData->item_no ?? '';
                    $bookingItem->item_type = $product['item_type'] ;
                    $bookingItem->product_name = $product['product_name']  ?? $productData->product_name ?? '';
                    $bookingItem->qty = $product['quantity']  ?? 1;
                    $bookingItem->gstprice = $product['gst']  ?? $productData->dediscount_percentages ?? 0;
                    $bookingItem->unit_price = $product['unit_price']  ?? $productData->sales_price ?? 0.00;
                    $bookingItem->linetotal = $product['line_total']  ?? $linetotal ?? 0.00;
                    $bookingItem->warrenty = $product['warranty']  ?? $productData->warranty ?? '';
                    $bookingItem->location = $product['warehouse_id'] ?? 'N/A';
                    $bookingItem->taxpercentage = $product['taxpercentage']  ?? $productData->tax ?? 0;
                    $bookingItem->taxamount = $product['taxamount']  ?? $taxAmount ?? 0;
                    $bookingItem->totalamount = $product['totalamount'] ?? $totalAmountIncTax ?? 0;
                    $bookingItem->uom = $product['uom']  ?? $productData->uom ?? '';
                    $bookingItem->uom_name= $product['uom_name']  ?? $productData->uom_name ?? '';
                    $bookingItem->comment= $product['comment']  ?? $productData->comment ?? '';
                    $bookingItem->save();
        //        }
            }
        } else {
            return response()->json(['message' => 'No products found or invalid data'], 400);
        }
 
        return response()->json(['message' => 'Quotation and items updated successfully'], 200);
    }
 
    return response()->json(['message' => 'Quotation not found'], 404);
}



public function editdetails($id)
{
    $bookingdetails = Booking::FindOrFail($id);
    return view('booking.editdetails',compact('bookingdetails'));
}
public function updatedetails(Request $request, $id)
{
    $request->validate([
        'requested_date' => 'required|date',
        'requested_time' => 'required',
        'city'           => 'required|string|max:255',
      
        'landmark'       => 'nullable|string|max:255',
    ]);

    $booking = Booking::findOrFail($id);

    $booking->update([
        'requested_date' => $request->input('requested_date'),
        'requested_time' => $request->input('requested_time'),
        'city'           => $request->input('city'),
      
        'landmark'       => $request->input('landmark'),
    ]);

    return redirect()->back()->with('success', 'Booking details updated successfully.');
}

public function storecomment(Request $request)
{
    $request->validate([
        'comment' => 'required|string',
        'booking_id' => 'required|exists:bookings,id',
    ]);

    $journal = new Booking_comment();
    $journal->comment = $request->comment;
    $journal->user_id = auth()->id();
    $journal->booking_id = $request->booking_id;
    $journal->save();

    
    $commentCount = session('comment_count', 0) + 1;
    session(['comment_count' => $commentCount]);

    return response()->json(['success' => true, 'count' => $commentCount]);
}


public function fetchcomment(Request $request)
{
    $booking_id = $request->booking_id;

    if (!$booking_id || !Booking::find($booking_id)) {
        return response()->json(['error' => 'First create a booking ID.'], 400);
    }

    $journals = Booking_comment::with('user')->where('booking_id', $booking_id)->latest()->get();
    
    return response()->json($journals);
}

public function updatecount(Request $request){
    $bookingId = $request->input('booking_id');

   
    $commentCount = session('comment_count', 0);
    if ($commentCount > 0) {
        session(['comment_count' => $commentCount - 1]);
    }

    return response()->json(['success' => true, 'count' => session('comment_count', 0)]);
}

public function clientcreate()
{
  

    $country_code = User::$country_code;
    $title = User::$title;
    $country = User::$country;
    $country_code_s = settings()['country_code'];
    $country_s = settings()['country'];
    $currency_s = settings()['CURRENCY_SYMBOL'];
    $company_name = settings()['company_name'];
    $timezone = settings()['timezone'];
    $customertemplate = CustomerTemplate::all();

   
    $companies = ClientDetail::distinct('company')->pluck('company', 'company')->filter()->toArray();
    $companies = ['' => 'Select a company'] + $companies;

    return view('client.bookingcreate', compact(
        'companies', 'country_code', 'title', 'country',
        'country_code_s', 'country_s', 'currency_s', 'company_name',
        'timezone', 'customertemplate'
    ));
}

}
