<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\WOType;
use App\Models\Workorder_scrap;
use GuzzleHttp\Client;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\WORequest;
use App\Models\BookingQuotation;
use App\Models\WorkOrder;
use App\Models\Inspection;
use App\Models\TempInspection;
use App\Models\GroupInspection;
use App\Models\SkillGroup;
use App\Models\SalesModule;
use App\Models\ScrapModule;
use App\Models\ServicePart;
use App\Models\BookingItems;
use App\Models\ClientDetail;
use App\Models\vehicle_make;
use App\Models\WorkOrderImg;
use Illuminate\Http\Request;
use App\Models\ServiceGroups;
use App\Models\ServiceMaster;
use App\Models\vehicle_model;
use App\Models\WOServicePart;
use App\Models\WOServiceTask;
use App\Models\AddWarrantyItems;
use App\Models\TechnicianBookingAppointment;
use App\Models\WOServiceAppointment;
use Illuminate\Support\Facades\Auth;
use App\Models\Warranty_registration;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\InspectionCompletedMail;
use App\Services\FirebaseService;

class WorkOrderController extends Controller
{
	protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }
	public function index()
	{
		if (auth()->user()->can('work order sidebar') || auth()->user()->type == 'super admin' || auth()->user()->type == 'owner') {
			$users = User::whereNot('type', 'client')->get();

			// Eager load related models
			$workorders = WorkOrder::with([
				'agent',
				'client',
			])->get();

			return view('workorder.index', compact('workorders', 'users'));
		} elseif (auth()->user()->type == 'technician') {
			$workorders = WorkOrder::whereRaw('JSON_CONTAINS(technician, \'["' . auth()->user()->id . '"]\')')
				->with(['agent', 'client'])
				->get();
			return view('workorder.index', compact('workorders'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function getWorkOrders(Request $request)
	{
		$query = WorkOrder::with(['agent', 'client']);

		// Technician filter (JSON)
		if (auth()->user()->type == 'technician') {
			$query->whereRaw('JSON_CONTAINS(technician, \'["' . auth()->user()->id . '"]\')');
		} elseif ($request->technician_id) {
			$query->whereRaw('JSON_CONTAINS(technician, \'["' . $request->technician_id . '"]\')');
		}

		// Appointment filter (JSON)
		if ($request->filled('appointment_date')) {
			$bookingIds = Booking::where('booking_date', $request->appointment_date)->pluck('id')->toArray();
			if (!empty($bookingIds)) {
				$query->where(function ($q) use ($bookingIds) {
					foreach ($bookingIds as $id) {
						$q->orWhereRaw('JSON_CONTAINS(booking, \'[' . $id . ']\')');
					}
				});
			} else {
				$query->whereRaw('0 = 1'); // no bookings
			}
		}

		// Status / created_by
		if ($request->created_by) {
			$query->where('created_by', $request->created_by);
		}

		if ($request->status) {
			$query->where('status', $request->status);
		}

		return DataTables::of($query->orderByDesc('id'))
			->addIndexColumn()
			->addColumn('wo_id', fn($row) => '#WO-' . $row->id)
			->addColumn('customer', fn($row) => $row->client?->full_name)
			->addColumn('service_group', fn($row) => $row->serviceGroupRecord()?->name ?? '')
			->addColumn('phone', fn($row) => '+' . preg_replace('/[^0-9]/', '', $row->client?->ccm) . $row->client?->phone_number)
			->addColumn('city', fn($row) => $row->bookings?->city ?? '')
			->addColumn('v_reg', fn($row) => $row->vehicleRecord()?->rego ?? '')
			->addColumn('v_model', fn($row) => $row->vehicleRecord()?->vehicle_models?->model_name ?? '')
			->addColumn('technician', fn($row) => $row->technicianRecord()?->full_name ?? '')
			->addColumn('appointment', fn($row) => optional($row->bookingRecord)?->booking_date . ' ' . optional($row->bookingRecord)?->booking_time)
			->addColumn('status', function ($row) {
				$colors = [
					'Open' => '#1e90ff',
					'Work Completed' => 'green',
					'Pending' => '#ffc107',
					'Paid' => 'green',
					'Accepted' => '#007bff',
					'Confirmed' => '#ff6347',
					'OnHold' => 'rgb(40,131,167)',
					'Enroute' => 'rgb(40,131,167)',
					'Invoiced' => 'red',
					'Inspection Completed'=>'#178236',
                    'Start Inspecion'=>'#FF8904'
				];
				$badgeColor = $colors[$row->status] ?? '#4e89c4';
				return '<span class="badge" style="width:75px; background-color: ' . $badgeColor . '; color:white;">' . $row->status . '</span>';
			})
			->addColumn('agent', fn($row) => $row->agent?->first_name)
			->addColumn('action', function ($row) {
				$btn = '';
				if (auth()->user()->can('show work order')) {
					$btn .= '<a class="text-success" href="' . route('workorder.show', $row->id) . '"><img src="' . asset('assets/img/icons/eye.svg') . '" style="width:25px;height:25px;"></a> ';
				}
				if (auth()->user()->can('edit work order')) {
					$btn .= '<a class="text-success" href="' . route('workorder.edit', $row->id) . '"><img src="' . asset('assets/img/icons/edit.svg') . '" style="width:25px;height:25px;"></a> ';
				}
				if (auth()->user()->can('delete work order')) {
					$btn .= '<a class="text-danger confirm_dialog" href="#"><img src="' . asset('assets/img/icons/trash.svg') . '" style="width:25px;height:25px;"></a>';
				}
				return $btn;
			})
			->filter(function ($query) use ($request) {
				if ($request->has('search') && $request->search['value'] != '') {
					$search = strtolower($request->search['value']);
					$query->where(function ($q) use ($search) {
						$q->whereRaw("CAST(id AS CHAR) LIKE ?", ["%{$search}%"])
							->orWhereHas('client', function ($q2) use ($search) {
								$q2->whereRaw("LOWER(CONCAT(first_name,' ',last_name)) LIKE ?", ["%{$search}%"])
									->orWhereRaw("phone_number LIKE ?", ["%{$search}%"]);
							})
							->orWhereHas('agent', function ($q2) use ($search) {
								$q2->whereRaw("LOWER(CONCAT(first_name,' ',last_name)) LIKE ?", ["%{$search}%"]);
							})
							->orWhereRaw("LOWER(status) LIKE ?", ["%{$search}%"]);
					});
				}
			})
			->rawColumns(['status', 'action'])
			->make(true);
	}

	public function workorderSearch(Request $request)
	{
		$query = WorkOrder::with('agent');

		// Filter by technician
		if ($request->filled('technician_id')) {
			$query->whereRaw('JSON_CONTAINS(technician, \'["' . $request->technician_id . '"]\')');
		}

		// Filter by agent (created_by)
		if ($request->filled('created_by')) {
			$query->where('created_by', $request->created_by);
		}

		// Filter by status
		if ($request->filled('warehouse_id')) {
			$query->where('status', $request->warehouse_id);
		}

		// Filter by booking date (via booking JSON column)
		if ($request->filled('appointment_date')) {
			$workorderIds = \App\Models\Booking::where('booking_date', $request->appointment_date)
				->pluck('id')
				->toArray();

			$query->where(function ($q) use ($workorderIds) {
				foreach ($workorderIds as $id) {
					$q->orWhereRaw('JSON_CONTAINS(booking, \'[' . $id . ']\')');
				}
			});
		}

		$workorders = $query->get();

		$users = User::whereNot('type', 'client')->get();

		return view('workorder.index', compact('workorders', 'users'));
	}

	public function create()
	{
		if (\Auth::user()->can('create work order')) {
			$clients = User::where('parent_id', parentId())
				->where('type', 'client')
				->get()
				->mapWithKeys(function ($client) {

					$companyName = $client->clients->company ?? '--';

					return [
						$client->id => $client->first_name . ' | ' . ucfirst($client->client_type) . ' | ' . $companyName
					];
				});

			$workOrder = '';

			$country_code = User::$country_code;
			$country = User::$country;

			$country_code_s = settings()['country_code'];
			$country_s = settings()['country'];

			$servicegroups = ServiceGroups::get();

			return view('workorder.create', compact('clients', 'country_code', 'country', 'country_code_s', 'country_s', 'servicegroups'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function clientfetch($id)
	{
		if (\Auth::user()->can('create work order')) {
			$customer = User::where('id', $id)->where('type', 'client')->first();

			if (!$customer) {
				return response()->json(['error' => 'Customer not found'], 404);
			}

			$country_id = $customer->country;
			$country_name = User::$country[$country_id] ?? null;

			return response()->json([
				'full_name' => $customer->first_name,
				'phone' => $customer->phone_number,
				'email' => $customer->email,
				'type' => $customer->client_type === 'corporate' ? 'Corporate' : 'Individual',
				'country_id' => $country_id,
				'country_name' => $country_name,
			]);
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function store(Request $request)

	{
		if (\Auth::user()->can('create work order')) {
			try {

				$validatedData = $request->validate([
					'created_date' => 'required',
					'subject' => 'required|string',
					'customer_id' => 'required|exists:users,id',
					'service_group' => 'required|string',
					'service_location' => 'required|string',
					'inspection' => 'required|string',
					'direct_wokorder' => '1',
				]);


				$coordinates = $this->getCoordinates($validatedData['service_location']);

				if ($coordinates) {
					$validatedData['service_lat'] = $coordinates['lat'];
					$validatedData['service_lng'] = $coordinates['lng'];
				} else {
					return redirect()->back()->with('error', __('Could not determine coordinates for the given service location.'));
				}
				$validatedData['direct_workorder'] = 1;
				$validatedData['status'] = 'Initiated';
				$workorderid = WorkOrder::create($validatedData);


				$clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('first_name', 'id');
				$workOrder = WorkOrder::find($workorderid->id);
				$Workorder_scraps = Workorder_scrap::where('wo_id', $workOrder->id)->get();
				$vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
				$bookingIds = json_decode($workOrder->booking, true) ?? [];
				$inspectionIds = json_decode($workOrder->inspections, true) ?? [];
				$technicianIds = json_decode($workOrder->technician, true) ?? [];
				$invoiceIds = json_decode($workOrder->invoive, true) ?? [];
				$paymentIds = json_decode($workOrder->payment, true) ?? [];

				//    $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
				$bookings = Booking::whereIn('id', $bookingIds)->get();
				$inspections = Inspection::whereIn('id', $inspectionIds)->get();
				$technicians = User::whereIn('id', $technicianIds)->get();
				$invoices = Invoice::whereIn('id', $invoiceIds)->get();
				$payments = Payment::whereIn('id', $paymentIds)->get();

				$availabletechnicians = User::with('clients')->where('parent_id', parentId())->where('type', 'technician')->get();
				$productItems = BookingItems::whereIn('quotation_id', $bookingIds)
					->get()
					->groupBy('booking_id');

				return redirect()->route('workorder.edit', ['workorder' => $workOrder->id])
					->with(compact('clients', 'workOrder', 'Workorder_scraps', 'vehicles', 'bookings', 'inspections', 'technicians', 'invoices', 'payments', 'productItems', 'availabletechnicians'));
			} catch (\Exception $e) {
				dd($e->getMessage());
			}
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	private function getCoordinates($address)
	{
		$apiKey = env('GOOGLE_MAP_KEY');
		$address = urlencode($address);

		$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

		try {
			$response = file_get_contents($url);
			$json = json_decode($response, true);

			if (isset($json['results'][0])) {
				$location = $json['results'][0]['geometry']['location'];
				return [
					'lat' => $location['lat'],
					'lng' => $location['lng']
				];
			}
			return null;
		} catch (\Exception $e) {
			return null;
		}
	}

	public function edit($id)
	{ 
       // dd($id);
		$settings = settings();

		$workOrder = WorkOrder::findOrFail($id);
		$clients = User::where('id', $workOrder->customer_id)->get();
		$vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
		$bookingIds = json_decode($workOrder->booking, true) ?? [];
		$inspectionIds = json_decode($workOrder->inspections, true) ?? [];
		$technicianIds = json_decode($workOrder->technician, true) ?? [];
		$invoiceIds = json_decode($workOrder->invoice, true) ?? [];
		$paymentIds = json_decode($workOrder->payment, true) ?? [];
		$appointment = $workOrder->appointment ?? null;
		$vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
		$skillgroups = SkillGroup::all();
		$Workorder_scraps = Workorder_scrap::where('wo_id', $workOrder->id)->get() ?? [];
		// dd($Workorder_scraps);
		$bookings = Booking::whereIn('id', $bookingIds)->get()->map(function ($booking) {
			$serviceGroupIds = json_decode($booking->service_group, true);
			$skillGroupIds = json_decode($booking->skill_group, true);

			// Skill Group Names
			if (is_array($skillGroupIds) && count($skillGroupIds) > 0) {
				$skillGroupNames = SkillGroup::whereIn('id', $skillGroupIds)->pluck('group_name')->toArray();
				$booking->skill_group_names = implode(', ', $skillGroupNames);
			} else {
				$booking->skill_group_names = null;
			}
//
			// Service Group Names
			if (is_array($serviceGroupIds) && count($serviceGroupIds) > 0) {
				$serviceGroupNames = ServiceGroups::whereIn('id', $serviceGroupIds)->pluck('name')->toArray();
				$booking->service_group_names = implode(', ', $serviceGroupNames);
			} else {
				$booking->service_group_names = null;
			}

			return $booking;
		});

//dd($bookingIds);

		foreach (Booking::whereIn('id', $bookingIds)->get() as $book) {
			$book->status = "Workorder";
			$book->save();
		}
		$warrantyRegistrations = [];
		foreach ($bookings as $booking) {
			if ($booking->warrantyregisterations) {

				$warrantyRegistrations[] = Warranty_registration::with('product')
					->where('id', $booking->warrantyregisterations)
					->first();
			}
		}
			
		$inspection = Inspection::where('booking',$bookingIds)->whereNot('status','cancelled')->first();
		$groups = $inspection ? GroupINspection::whereIn('id',json_decode($inspection->groups))->pluCk('code')->toArray() : [];
        
		$quotations = DB::table('booking_quotations')
			->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
			->where('booking_quotations.booking_id', $bookingIds)
			->where('status', 'confirmed')

			->select(
				'booking_quotations.id as quotation_id',
				'booking_quotations.send_date',
				'booking_quotations.status',
				'booking_items.product_id',
				'booking_items.id',
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
			

		$workorderquotations = DB::table('booking_quotations')
			->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
			->where('booking_quotations.workorder_id', $id)
			->where('status', 'confirmed')
			->select(
				'booking_quotations.id as quotation_id',
				'booking_quotations.send_date',
				'booking_quotations.status',
				'booking_items.product_id',
				'booking_items.item_no',
				'booking_items.item_type',
				'booking_items.product_name',
				'booking_items.qty',
				'booking_items.gstprice',
				'booking_items.linetotal',
				'booking_items.warrenty',
				'booking_items.uom',
				'booking_items.uom_name',
				'booking_items.location',
				'booking_items.unit_price',
				'booking_items.taxamount',
				'booking_items.taxpercentage',
				'booking_items.totalamount'

			)
			->get()
			->groupBy('quotation_id');

		if ($quotations->isEmpty()) {
			$quotations = $workorderquotations;
		}

		$firstBooking = $bookings->first();
		$bookingworkorderid = $firstBooking ? $firstBooking->workorderid : null;

		$inspections = Inspection::whereIn('id', $inspectionIds)->get();
		$serviceCoordinates = $this->getCoordinatesFromAddress($workOrder->service_location);

		$technicians = User::whereIn('id', $technicianIds)
			->whereHas('technicianlocation', function ($query) {
				$query->whereNotNull('user_id');
			})
			->with('technicianlocation')
			->get();

		$invoices = Invoice::whereIn('id', $invoiceIds)->get();
		$payments = Payment::whereIn('id', $paymentIds)->get();

		$searchvehicles = Vehicle::where('client', $workOrder->customer_id)->get();
		$searchbookings = Booking::where('client', $workOrder->customer_id)->get();
		$searchinspections = Inspection::where('customer', $workOrder->customer_id)->get();
		$searchtechnicians = User::where('type', 'technician')->get();
		$searchinvoices = Invoice::where('client', $workOrder->customer_id)->get();
		$searchpayments = Payment::where('client', $workOrder->customer_id)->get();
		$availabletechnicians = User::where('type', 'technician')
			->whereHas('workHours')
			->with('workHours')
			->get();

		$totaltechnicians = User::with('clients')->where('parent_id', parentId())->where('type', 'technician')->get();

		$salesModule = SalesModule::all();
		$scrap = ScrapModule::first();

		$vm = vehicle_make::all()->pluck('make_name', 'id');
		$vmod = vehicle_model::all()->pluck('model_name', 'id');
		$country_code = User::$country_code;

		$workOrderImg = WorkOrderImg::where('order_id', $id)->get();

		return view('workorder.edit', compact('workOrderImg', 'vm', 'vmod', 'appointment', 'inspection','groups','Workorder_scraps', 'country_code', 'totaltechnicians', 'settings', 'clients', 'workOrder', 'vehicles', 'skillgroups', 'bookings', 'inspections', 'technicians', 'invoices', 'payments', 'searchvehicles', 'searchbookings', 'searchinspections', 'searchtechnicians', 'searchinvoices', 'searchpayments', 'availabletechnicians', 'bookingworkorderid', 'quotations', 'warrantyRegistrations', 'salesModule', 'scrap'));
	}

	public function show($id)
	{
		$settings = settings();

		$workOrder = WorkOrder::findOrFail($id);
		$clients = User::where('id', $workOrder->customer_id)->get();
		$vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
		$bookingIds = json_decode($workOrder->booking, true) ?? [];
		$inspectionIds = json_decode($workOrder->inspections, true) ?? [];
		$technicianIds = json_decode($workOrder->technician, true) ?? [];
		$invoiceIds = json_decode($workOrder->invoice, true) ?? [];
		$paymentIds = json_decode($workOrder->payment, true) ?? [];
		$appointment = $workOrder->appointment ?? null;
		$vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
		$skillgroups = SkillGroup::all();

		$bookings = Booking::whereIn('id', $bookingIds)->get()->map(function ($booking) {
			$serviceGroupIds = json_decode($booking->service_group, true);
			$skillGroupIds = json_decode($booking->skill_group, true);

			// Skill Group Names
			if (is_array($skillGroupIds) && count($skillGroupIds) > 0) {
				$skillGroupNames = SkillGroup::whereIn('id', $skillGroupIds)->pluck('group_name')->toArray();
				$booking->skill_group_names = implode(', ', $skillGroupNames);
			} else {
				$booking->skill_group_names = null;
			}

			// Service Group Names
			if (is_array($serviceGroupIds) && count($serviceGroupIds) > 0) {
				$serviceGroupNames = ServiceGroups::whereIn('id', $serviceGroupIds)->pluck('name')->toArray();
				$booking->service_group_names = implode(', ', $serviceGroupNames);
			} else {
				$booking->service_group_names = null;
			}

			return $booking;
		});



		foreach (Booking::whereIn('id', $bookingIds)->get() as $book) {
			$book->status = "Workorder";
			$book->save();
		}
		$warrantyRegistrations = [];
		foreach ($bookings as $booking) {
			if ($booking->warrantyregisterations) {

				$warrantyRegistrations[] = Warranty_registration::with('product')
					->where('id', $booking->warrantyregisterations)
					->first();
			}
		}

		$quotations = DB::table('booking_quotations')
			->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
			->where('booking_quotations.booking_id', $bookingIds)
			->where('status', 'confirmed')

			->select(
				'booking_quotations.id as quotation_id',
				'booking_quotations.send_date',
				'booking_quotations.status',
				'booking_items.product_id',
				'booking_items.id',
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

		$workorderquotations = DB::table('booking_quotations')
			->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
			->where('booking_quotations.workorder_id', $id)
			->where('status', 'confirmed')
			->select(
				'booking_quotations.id as quotation_id',
				'booking_quotations.send_date',
				'booking_quotations.status',
				'booking_items.product_id',
				'booking_items.item_no',
				'booking_items.item_type',
				'booking_items.product_name',
				'booking_items.qty',
				'booking_items.gstprice',
				'booking_items.linetotal',
				'booking_items.warrenty',
				'booking_items.uom',
				'booking_items.uom_name',
				'booking_items.location',
				'booking_items.unit_price',
				'booking_items.taxamount',
				'booking_items.taxpercentage',
				'booking_items.totalamount'

			)
			->get()
			->groupBy('quotation_id');

		if ($quotations->isEmpty()) {
			$quotations = $workorderquotations;
		}

		$firstBooking = $bookings->first();
		$bookingworkorderid = $firstBooking ? $firstBooking->workorderid : null;

		$inspections = Inspection::whereIn('id', $inspectionIds)->get();
		$serviceCoordinates = $this->getCoordinatesFromAddress($workOrder->service_location);

		$technicians = User::whereIn('id', $technicianIds)
			->whereHas('technicianlocation', function ($query) {
				$query->whereNotNull('user_id');
			})
			->with('technicianlocation')
			->get();

		$invoices = Invoice::whereIn('id', $invoiceIds)->get();
		$payments = Payment::whereIn('id', $paymentIds)->get();

		$searchvehicles = Vehicle::where('client', $workOrder->customer_id)->get();
		$searchbookings = Booking::where('client', $workOrder->customer_id)->get();
		$searchinspections = Inspection::where('customer', $workOrder->customer_id)->get();
		$searchtechnicians = User::where('type', 'technician')->get();
		$searchinvoices = Invoice::where('client', $workOrder->customer_id)->get();
		$searchpayments = Payment::where('client', $workOrder->customer_id)->get();
		$availabletechnicians = User::where('type', 'technician')
			->whereHas('workHours')
			->with('workHours')
			->get();

		$totaltechnicians = User::with('clients')->where('parent_id', parentId())->where('type', 'technician')->get();

		$salesModule = SalesModule::all();
		$scrap = ScrapModule::first();

		$vm = vehicle_make::all()->pluck('make_name', 'id');
		$vmod = vehicle_model::all()->pluck('model_name', 'id');
		$country_code = User::$country_code;

		$workOrderImg = WorkOrderImg::where('order_id', $id)->get();

		return view('workorder.show', compact('workOrderImg', 'vm', 'vmod', 'appointment', 'country_code', 'totaltechnicians', 'settings', 'clients', 'workOrder', 'vehicles', 'skillgroups', 'bookings', 'inspections', 'technicians', 'invoices', 'payments', 'searchvehicles', 'searchbookings', 'searchinspections', 'searchtechnicians', 'searchinvoices', 'searchpayments', 'availabletechnicians', 'bookingworkorderid', 'quotations', 'warrantyRegistrations', 'salesModule', 'scrap'));
	}

	public function searchtabs(Request $request, $id)
	{


		$workOrder = WorkOrder::findOrFail($id);
		$fieldToUpdate = null;
		$searchVehicle = $request->search_vehicle;

		switch ($request->searchtype) {
			case 'vehicle':
				$fieldToUpdate = 'vehicle';
				break;
			case 'booking':
				$fieldToUpdate = 'booking';
				break;
			case 'inspection':
				$fieldToUpdate = 'inspections';
				break;
			case 'invoice':
				$fieldToUpdate = 'invoice';
				break;
			case 'payment':
				$fieldToUpdate = 'payment';
				break;
			default:
				return redirect()->route('workorder.edit', ['workorder' => $id])->withErrors('Invalid search type');
		}
		$existingData = json_decode($workOrder->$fieldToUpdate, true) ?? [];
		$existingData[] = $searchVehicle;
		$uniqueData = array_unique($existingData);
		$workOrder->$fieldToUpdate = json_encode($uniqueData);
		$workOrder->save();
		return redirect()->route('workorder.edit', ['workorder' => $id]);
	}

	public function techniciansearchtabs(Request $request, $id)
	{


		$booking = Booking::find($id);
		$serviceId = $booking->service_group;
		$ServiceMaster = ServiceMaster::find(json_decode($serviceId));
		$skillId = $ServiceMaster->skillId;
		$technicians = User::where('type', 'technician')->where('skills', $skillId)->get();
		$apiKey = googleApiKey();
		$client = new Client();
		$woAddress = $request->query('woaddress');
		$currentDate = $request->query('currentDate');


		$mappedTechnicians = $technicians->map(function ($technician) use ($client, $apiKey, $woAddress, $ServiceMaster, $currentDate) {
			$technicianAddress = ClientDetail::where('user_id', $technician->id)->first()->service_address;

			$currentDateCarbon = Carbon::createFromFormat('l, F j, Y', $currentDate);
			$currentDate = $currentDateCarbon->format('Y-m-d');

			$worequests = WORequest::where('assign', $technician->id)
				->where('status', 1)
				->where('preferred_date', $currentDate)
				->get();

			$timeBookings = [];
			foreach ($worequests as $worequest) {
				$preferredTime = $worequest->preferred_time;
				$time = Carbon::createFromFormat('H:i:s', $preferredTime);
				$adjustedTime = $time->copy()->addHours(2);
				$originalTimeString = $time->format('H:i');
				$adjustedTimeString = $adjustedTime->format('H:i');
				$timeBookings[] = [
					'original_time' => $originalTimeString,
					'adjusted_time' => $adjustedTimeString
				];
			}

			try {
				$response = $client->request('GET', 'https://maps.googleapis.com/maps/api/distancematrix/json', [
					'query' => [
						'origins' => $technicianAddress,
						'destinations' => $woAddress,
						'key' => $apiKey,
						'units' => 'metric'
					]
				]);
				$data = json_decode($response->getBody(), true);
				$distance = $data['rows'][0]['elements'][0]['distance']['text'] ?? 'N/A';
			} catch (\Exception $e) {
				$distance = 'Error fetching distance';
			}
			return [
				'id' => $technician->id,
				'first_name' => $technician->first_name,
				'profile' => asset($technician->profile),
				'distance' => $distance,
				'cost' => $ServiceMaster->price,
				'availabilityPercentage' => $timeBookings,
			];
		});
		return response()->json($mappedTechnicians);
	}

	public function assigntechnicianwo(Request $request)
	{
		$request->validate([
			'techid' => 'required|exists:users,id',
			'woid' => 'required|exists:work_orders,id',
		]);
		$workorderId = $request->input('woid');
		$techid = $request->input('techid');
		$currentDate = $request->input('currentDate');

		$workOrder = WorkOrder::findOrFail($workorderId);
		$booking = Booking::find(json_decode($workOrder->booking))->first();
		$existingData = json_decode($workOrder->technician, true) ?? [];
		$existingData[] = $techid;
		$uniqueData = array_unique($existingData);
		$workOrder->technician = json_encode($uniqueData);
		$workOrder->save();

		$currentDateCarbon = Carbon::createFromFormat('l, F j, Y', $currentDate);
		$currentDate = $currentDateCarbon->format('Y-m-d');

		$worequest = WORequest::create([
			'client' => $workOrder->customer_id,
			'woid' => $workOrder->id,
			'status' => '0',
			'assign' => $techid,
			'preferred_date' => $currentDate,
			'preferred_time' => $booking->scheduled_time
		]);
		if ($worequest) {
			return response()->json([
				'success' => true,
				'message' => 'Technician assigned successfully!',
			]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Failed to assign technician.'
			], 400);
		}
	}



	public function update(Request $request, $id)
	{
		$validatedData = $request->validate([
			'created_date' => 'required|date',
			'subject' => 'required|string',
			'customer_id' => 'required|exists:users,id',
			'service_group' => 'required|string',
			'service_location' => 'required|string',
			'inspection' => 'required|string',
			'effort_hours' => 'required|numeric',
		]);
		$workOrder = WorkOrder::find($id);
		if ($workOrder) {
			$workOrder->update($validatedData);
			return redirect()->back()->with('success', 'Work order updated successfully.');
		} else {
			return redirect()->back()->with('error', 'Work order not found.');
		}
	}

	public function destroy($id)
	{
		//if (\Auth::user()->can('delete work order')) {
			$workOrder = WorkOrder::find($id);
			$workOrder->delete();
			return redirect()->back()->with('success', __('Work Order successfully deleted.'));
		// } else {
		// 	return redirect()->back()->with('error', __('Permission denied.'));
		// }
	}

	public function workOrderNumber()
	{
		$lastWorkorder = WorkOrder::where('parent_id', parentId())->latest()->first();
		if ($lastWorkorder == null) {
			return 1;
		} else {
			return $lastWorkorder->wo_id + 1;
		}
	}

	public function getServicePart(Request $request)
	{
		$servicePart = ServicePart::find($request->id);
		return response()->json($servicePart);
	}

	public function servicePartDestroy(Request $request)
	{
		if (\Auth::user()->can('delete workorder service & part')) {
			if (isset($request->id) && !empty($request->id)) {
				$servicePart = WOServicePart::find($request->id);
				$servicePart->delete();
			}

			return 1;
		} else {
			return redirect()->back()->with('error', __('Permission denied.'));
		}
	}

	public function workorderStatus(Request $request, $workorderId)
	{


		$workorder = WorkOrder::find($workorderId);
		$workorder->status = $request->status;
		$workorder->save();

		return redirect()->back()->with('success', __('Workorder status successfully changed.'));
	}


	public function serviceTaskCreate($id)
	{
		$workorder = WorkOrder::find($id);
		$woServices = $workorder->services;
		$status = WOServiceTask::$status;
		return view('workorder.service_task_create', compact('workorder', 'woServices', 'status'));
	}

	public function serviceTaskStore(Request $request, $id)
	{
		if (\Auth::user()->can('create workorder service task')) {
			$validator = \Validator::make(
				$request->all(),
				[
					'service' => 'required',
					'service_task' => 'required',
					'duration' => 'required',
				]
			);
			if ($validator->fails()) {
				$messages = $validator->getMessageBag();
				return redirect()->back()->with('error', $messages->first())->with('active_tab', 'service_task')->withInput();
			}

			$task = new WOServiceTask();
			$task->wo_id = $id;
			$task->service_part_id = $request->service;
			$task->service_task = $request->service_task;
			$task->duration = $request->duration;
			$task->description = $request->description;
			$task->status = $request->status;
			$task->save();

			return redirect()->back()->with('success', __('Service task successfully created.'))->with('active_tab', 'service_task');
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function serviceTaskEdit($woId, $taskId)
	{
		$workorder = WorkOrder::find($woId);
		$woServices = $workorder->services;
		$status = WOServiceTask::$status;
		$task = WOServiceTask::find($taskId);
		return view('workorder.service_task_edit', compact('workorder', 'woServices', 'status', 'task'));
	}

	public function serviceTaskUpdate(Request $request, $woId, $taskId)
	{
		if (\Auth::user()->can('edit workorder service task')) {
			$validator = \Validator::make(
				$request->all(),
				[
					'service' => 'required',
					'service_task' => 'required',
					'duration' => 'required',
				]
			);
			if ($validator->fails()) {
				$messages = $validator->getMessageBag();
				return redirect()->back()->with('error', $messages->first())->withInput();
			}

			$task = WOServiceTask::find($taskId);
			$task->service_part_id = $request->service;
			$task->service_task = $request->service_task;
			$task->duration = $request->duration;
			$task->description = $request->description;
			$task->status = $request->status;
			$task->save();

			return redirect()->back()->with('success', __('Service task successfully updated.'))->with('active_tab', 'service_task');
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function serviceTaskDestroy($woId, $taskId)
	{
		if (\Auth::user()->can('delete workorder service task')) {
			$task = WOServiceTask::find($taskId);
			$task->delete();
			return redirect()->back()->with('success', __('Service task successfully deleted.'))->with('active_tab', 'service_task');
		} else {
			return redirect()->back()->with('error', __('Permission denied.'));
		}
	}

	public function serviceAppointment($wo_id)
	{
		$status = WOServiceAppointment::$status;
		$serviceAppointment = WOServiceAppointment::where('wo_id', $wo_id)->first();
		return view('workorder.service_appointment', compact('wo_id', 'status', 'serviceAppointment'));
	}

	public function serviceAppointmentStore(Request $request, $id)
	{

		if (\Auth::user()->can('create service appointment')) {
			$validator = \Validator::make(
				$request->all(),
				[
					'start_date' => 'required',
					'start_time' => 'required',
					'end_date' => 'required',
					'end_time' => 'required',
					'status' => 'required',
				]
			);
			if ($validator->fails()) {
				$messages = $validator->getMessageBag();
				return redirect()->back()->with('error', $messages->first())->with('active_tab', 'service_appointment')->withInput();
			}

			$appointment = WOServiceAppointment::where('wo_id', $id)->first();
			if (empty($appointment)) {
				$appointment = new WOServiceAppointment();
			}
			$appointment->wo_id = $id;
			$appointment->start_date = $request->start_date;
			$appointment->start_time = $request->start_time;
			$appointment->end_date = $request->end_date;
			$appointment->end_time = $request->end_time;
			$appointment->notes = $request->notes;
			$appointment->status = $request->status;
			$appointment->parent_id = parentId();
			$appointment->save();

			return redirect()->back()->with('success', __('Service appointment successfully created.'))->with('active_tab', 'service_appointment');
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function serviceAppointmentDestroy($woId)
	{
		if (\Auth::user()->can('delete service appointment')) {
			$appointment = WOServiceAppointment::where('wo_id', $woId)->first();
			$appointment->delete();
			return redirect()->back()->with('success', __('Service appointment successfully deleted.'))->with('active_tab', 'service_appointment');
		} else {
			return redirect()->back()->with('error', __('Permission denied.'));
		}
	}
	public function accepted($accepted)
	{
		$data = (object) Crypt::decrypt($accepted);
		WorkOrder::where('id', $data->id)->update(['accepted' => $data->accepted]);
		if ($data->accepted == 1) {
			return redirect()->back()->with('success', __('Work Order successfully accepte'));
		} else {
			return redirect()->back()->with('success', __('Work Order successfully reject'));
		}
	}

	public function getVehicleInfo($id)
	{
		$vehicle = Vehicle::find($id);

		if ($vehicle) {
			return response()->json([
				'make' => $vehicle->v_make,
				'model' => $vehicle->model_series,
				'vin' => $vehicle->vin,
				'id' => $vehicle->id,
				'engine_number' => $vehicle->engine_number,
				'last_service' => $vehicle->last_service,
				'odometer' => $vehicle->odometer,
			]);
		}

		return response()->json(['error' => 'Vehicle not found'], 404);
	}

	public function getTestPage()
	{

		$userdetails = User::has('technicianlocation')->with('technicianlocation')->get();

		return view('workorder.test', compact('userdetails'));
	}
	public function vehicledestroy(Request $request, $workOrderId, $vehicleId)
	{
		try {
			$workOrder = WorkOrder::findOrFail($workOrderId);
			$vehicleIds = json_decode($workOrder->vehicle, true);

			if (($key = array_search($vehicleId, $vehicleIds)) !== false) {
				unset($vehicleIds[$key]);
				$workOrder->vehicle = json_encode(array_values($vehicleIds));
				$workOrder->save();
			} else {
				return redirect()->back()->with('error', 'Vehicle not found in the work order.');
			}

			return redirect()->back()->with('success', 'Vehicle deleted successfully from the work order.');
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'Error deleting vehicle: ' . $e->getMessage());
		}
	}



	public function bookingdestroy($workOrderId, $bookingId)
	{
		try {
			$workOrder = WorkOrder::findOrFail($workOrderId);

			$bookingIds = json_decode($workOrder->booking, true);

			if (($key = array_search($bookingId, $bookingIds)) !== false) {
				unset($bookingIds[$key]);

				$workOrder->booking = json_encode(array_values($bookingIds));
				$workOrder->save();
			}

			return redirect()->back()->with('success', 'Booking deleted successfully from the work order.');
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'Error deleting booking: ' . $e->getMessage());
		}
	}

	public function paymentdestroy($workOrderId, $paymentId)
	{
		try {
			$workOrder = WorkOrder::findOrFail($workOrderId);

			$paymentIds = json_decode($workOrder->payment, true);

			if (($key = array_search($paymentId, $paymentIds)) !== false) {
				unset($paymentIds[$key]);

				$workOrder->payment = json_encode(array_values($paymentIds));
				$workOrder->save();
			}

			return redirect()->back()->with('success', 'Payment deleted successfully from the work order.');
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'Error deleting payment: ' . $e->getMessage());
		}
	}

	private function getCoordinatesFromAddress($address)
	{

		$apiKey = env('GOOGLE_MAP_KEY');
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

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

	public function getOne(Request $request)
	{
		//dd($request->all());
		$id = $request->id;
		$workOrder = WorkOrder::find($id);
		//dd($workOrder->inv);

		// Check if work order exists and has an invoice
		if (!$workOrder ) {
			return redirect()->back()->with('error', 'Work Order must have an invoice.');
		}

		$vehicle_id = json_decode($workOrder->vehicle, true);
		$bookingid = json_decode($workOrder->booking, true);

		$items = DB::table('booking_quotations')
			->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
			->where('booking_quotations.booking_id', $bookingid)
			->where('status', 'confirmed')
			->select(
				'booking_quotations.id as quotation_id',
				'booking_quotations.send_date',
				'booking_quotations.status',
				'booking_items.product_id',
				'booking_items.item_type',
				'booking_items.item_no',
				'booking_items.product_name',
				'booking_items.qty',
				'booking_items.gstprice',
				'booking_items.linetotal',
				'booking_items.warrenty',
				'booking_items.uom',
				'booking_items.uom_name',
				'booking_items.location',
				'booking_items.unit_price',
				'booking_items.taxamount',
				'booking_items.taxpercentage',
				'booking_items.totalamount'
			)
			->get()
			->groupBy('quotation_id');
			

		return response()->json([
			'customer' => $workOrder->client->first_name,
			'customer_id' => $workOrder->customer_id,
			'invoice' => $workOrder->inv,
			'vehicle' => $vehicle_id[0],
			'products' => $items
		]);
	}

	public function cancelworkorder(Request $request)
	{

		$request->validate([
			'workorderid' => 'required|exists:work_orders,id',
			'cancellation_note' => 'required|string|max:500',
			'cancellation_reason' => 'required|string|max:255',
		]);

		$workorderId = $request->input('workorderid');
		$cancellationNote = $request->input('cancellation_note');
		$cancellationReason = $request->input('cancellation_reason');


		$workorder = Workorder::findOrFail($workorderId);


		$bookingIds = json_decode($workorder->booking, true);


		if (!is_array($bookingIds) || empty($bookingIds)) {
			return redirect()->route('booking.index')->with('error', 'Invalid booking data in workorder.');
		}


		foreach ($bookingIds as $bookingId) {
			Booking::where('id', $bookingId)->update([
				'status' => 'canceled',
				'notes' => $cancellationNote,
				'reasons' => $cancellationReason,
			]);
		}


		$workorder->update([
			'status' => 'canceled',
			'notes' => $cancellationNote,
			'reasons' => $cancellationReason,
		]);


		return redirect()->route('workorder.index')->with('success', 'Booking and workorder canceled successfully.');
	}


	public function cancel($id)
	{

		$workorderid = $id;
		return view('workorder.cancel', compact('workorderid'));
	}
	public function getDetails($id)
	{
		$workOrder = WorkOrder::with('client', 'vehicl', 'inv')->find($id);

		if (!$workOrder) {
			return redirect()->back()->with('error', 'Work Order not found.');
		}

		$vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
		$vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
		$Workorder_scraps = Workorder_scrap::where('wo_id', $workOrder->id)->get();
		$bookingIds = json_decode($workOrder->booking, true) ?? [];
		$appointment = $workOrder->appointment ?? null;
		$servicegroups = ServiceGroups::get();
		$templates=TempInspection::all();

		$invoices = DB::table('invoices')
			->where('wo_id', $id)
			->get();

		$payments = DB::table('payments')
			->join('invoices', 'payments.invoice', '=', 'invoices.id')
			->where('invoices.wo_id', $id)
			->select('*')
			->get();
       
		$quotations = DB::table('booking_quotations')
			->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
			->whereIn('booking_quotations.booking_id', $bookingIds)
			->where('status', 'confirmed')
			->select(
				'booking_quotations.id as quotation_id',
				'booking_quotations.send_date',
				'booking_quotations.status',
				'booking_items.product_id',
				'booking_items.id',
				'booking_items.item_no',
				'booking_items.product_name',
				'booking_items.qty',
				'booking_items.unit_price',
				'booking_items.gstprice',
				'booking_items.linetotal',
				'booking_items.warrenty',
				'booking_items.location',
				'booking_items.taxamount',
				'booking_items.totalamount',
				'booking_items.uom',
				'booking_items.uom_name',
			)
			->get()
			->groupBy('quotation_id');


		foreach ($quotations as $quotation_id => $quotation) {
			foreach ($quotation as $item) {
				$existingWarrantyItem = AddWarrantyItems::where('workorderid', $id)
					->where('booking_item_id', $item->id)
					->first();


				if ($existingWarrantyItem) {
					$item->warranty_exists = true;
					$item->warranty_number = $existingWarrantyItem->warrantynumber;
					$item->from_date = $existingWarrantyItem->from_date;
					$item->to_date = $existingWarrantyItem->to_date;
				} else {
					$item->warranty_exists = false;
				}
			}
		}
		//dd($quotations);
		// New code for bookings
		$workOrder = WorkOrder::find($id);
		$vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
		$bookingIds = json_decode($workOrder->booking, true) ?? [];
		$inspection = Inspection::where('booking',$bookingIds[0])->whereNot('status','cancelled')->first();
        $inspection_id=$inspection?->id;
        $template = $inspection?->template;
		$quotationstatus = BookingQuotation::where('booking_id',$bookingIds[0])->first() ?? null;
      // dd($quotationstatus);
        $groups= json_decode($inspection?->groups) ?? [];
		 $groups= GroupInspection:: with(['mainpoints' => function ($query) use ($inspection_id) {
          $query->where('inspection_id', $inspection_id);
          }])->whereIn('id',$groups)->get();
//dd($groups);
		$bookings = Booking::whereIn('id', $bookingIds)->get()->map(function ($booking) {
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
		$warrantyRegistrations = [];
		foreach ($bookings as $booking) {
			if ($booking->warrantyregisterations) {

				$warrantyRegistrations[] = Warranty_registration::with('product')
					->where('id', $booking->warrantyregisterations)
					->first();
			}
		}

		$vm = vehicle_make::all()->pluck('make_name', 'id');
		$vmod = vehicle_model::all()->pluck('model_name', 'id');
		$country_code = User::$country_code;

		$workOrderImg = WorkOrderImg::where('order_id', $id)->get();
//dd($groups);
		return view('techdashboard.listwdetails', [
			'workOrder' => $workOrder,
			'Workorder_scraps' => $Workorder_scraps,
			'templates'=>$templates,
			'template'=>$template,
			'inspection'=>$inspection,
			'groups'=>$groups,
			'vehicles' => $vehicles,
			'quotations' => $quotations,
			'quotationstatus' => $quotationstatus,
			'invoices' => $invoices,
			'payments' => $payments,
			'appointment' => $appointment,
			'bookings' => $bookings,
			'warrantyRegistrations' => $warrantyRegistrations,
			'vm' => $vm,
			'vmod' => $vmod,
			'country_code' => $country_code,
			'workOrderImg' => $workOrderImg,
		]);
	}

	public function getWorkorderQuotatons(Request $request)
	{
 //dd($request->all()); 
         if($request->booking_id)
		 {
			$bookingIds=$request->booking_id;
		 }else{
		$workorder = WorkOrder::find($request->workorder);
		$bookingIds = json_decode($workorder->booking, true) ?? [];}

        $status= $request->status ?? 'confirmed';
		//dd($status);
		$quotations = DB::table('booking_quotations')
			->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
			->where('booking_quotations.booking_id', $bookingIds)
			->where('status', $status)
			->select(
				'booking_quotations.id as quotation_id',
				'booking_quotations.send_date',
				'booking_quotations.status',
				'booking_items.product_id',
				'booking_items.item_no',
				'booking_items.item_type',
				'booking_items.product_name',
				'booking_items.qty',
				'booking_items.gstprice',
				'booking_items.linetotal',
				'booking_items.warrenty',
				'booking_items.uom_name',
				'booking_items.uom',
				'booking_items.location',
				'booking_items.unit_price',
				'booking_items.taxamount',
				'booking_items.taxpercentage',
				'booking_items.totalamount',
				'booking_items.comment'

			)
			->get();
			


		return response()->json($quotations, 200);
	}
	public function getAllocateTechnician(Request $request, $id)
	{
		//dd($id);
		$workOrder = WorkOrder::findOrFail($id);
		//dd($workOrder);
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
            
		return view('workorder.gettechnician', compact('technicians', 'workOrder', 'todayDay', 'today'))->render();
	}

	private function calculateDistance($lat1, $lng1, $lat2, $lng2)
	{
		$earthRadius = 6371; // Radius of Earth in km

		$latDelta = deg2rad($lat2 - $lat1);
		$lngDelta = deg2rad($lng2 - $lng1);

		$a = sin($latDelta / 2) * sin($latDelta / 2) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
			sin($lngDelta / 2) * sin($lngDelta / 2);

		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		return $earthRadius * $c; // Distance in kilometers
	}

	public function updateTechStatus(Request $request)
	{
		$request->validate([
			'workorder' => 'required|exists:work_orders,id',
		]);

		try {
			$workOrder = WorkOrder::findOrFail($request->input('workorder'));
			$booking = Booking::where('workorderid',$workOrder->id)->first();
			$customer = User::find($workOrder->customer_id);
			$user = auth()->user();
			$currentDateTime = now();

			// Store previous status in session when moving to OnHold
			if ($request->input('status') == 'OnHold') {
				session(['previous_status' => $workOrder->status]);
			}

			// Update work order status
			$workOrder->update(['status' => $request->input('status')]);

			if($request->input('status') == 'Inspection Completed'){
				try {
					$this->firebase->sendNotification([
						'title' => "Alert For workorder ID: ".$workOrder->id,
						'type' => 'admin_alert',
						'section' => 'inspection',
						'authId' => $workOrder->created_by,
					]);
				} catch (\Throwable $th) {
					//throw $th;
				}

				try{
					$details = [
						'to' => config('mail.from.admin_address'),
						'to_name' => 'Admin Team',
						'from' => Auth::user()->email,
						'from_name' => 'Delta Turf Care',
						'subject' => 'Inspection Completed – Booking ID: ' . $booking->id,
						'heading' => 'Inspection Completed – Booking ID: #' . $booking->id,
						'body' => 'The inspection for the following work order has been completed by the technician.',
						'm_detail_booking' => $booking->id ?? '',
						'm_detail_workorder' =>$workOrder->id ??  '',
						'm_detail_customer' => $customer->first_name . ' ' . $customer->last_name ?? '',
						'user_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name ?? "Technician Name",
						'footer' => 'Thank you for choosing Delta Turf Care. For immediate assistance, call 800247365.',
					];
					// Send the email for inspection completed 
					Mail::send(new InspectionCompletedMail($details));
				} catch (Exception $e) {
				}
				
			}elseif($request->input('status') == 'Work Completed'){
				try {
					$this->firebase->sendNotification([
						'title' => "Alert For workorder ID: ".$workOrder->id,
						'type' => 'admin_alert',
						'section' => 'workComplete',
						'authId' => $workOrder->created_by,
					]);
				} catch (\Throwable $th) {
					//throw $th;
				}
				try{ 
					$details = [
						'to' => config('mail.from.admin_address'),
						'to_name' => 'Admin',
						'from' => Auth::user()->email,
						'from_name' => 'Delta Turf Care',
						'subject' => 'Work Order Completed – Work Order ID: ' . $workOrder->id . ' (' . $customer->first_name . ' ' . $customer->last_name . ')',
						'heading' => 'Work Order Completed – Work Order ID #' . $workOrder->id,
						'body' => 'The technician has marked the following work order as Completed.',
						'm_detail_booking' => $booking->id ?? '',
						'm_detail_workorder' =>$workOrder->id ??  '',
						'm_detail_customer' => $customer->first_name . ' ' . $customer->last_name ?? '',
						'user_name' => Auth::user()->first_name . ' '. Auth::user()->last_name ?? "Technician Name",
						'footer' => 'Thank you for choosing Delta Turf Care. For immediate assistance, call 800247365.',
					];
					// Send the email for work completed
					Mail::send(new InspectionCompletedMail($details));
				} catch (Exception $e) {
				}
			}

			$logData = [
				'user_id' => $user->id,
				'log' => 'Work order ' . $workOrder->id . ' status updated to "' . $request->input('status') . '" at ' . $currentDateTime->toDateTimeString(),
				'created_dateandtime' => $currentDateTime->toDateTimeString(),
				'sessionid' => session()->getId(),
				'workorderid' => $workOrder->id,
				'created_at' => $currentDateTime,
				'updated_at' => $currentDateTime,
			];

			// Handle log data based on status
			switch ($request->input('status')) {
				case 'Enroute':
					$logData['en_rout_time'] = $currentDateTime->toTimeString();
					break;
				case 'Start Work':
					$logData['start_time'] = $currentDateTime->toTimeString();
					break;
				case 'Work Completed':
					$logData['finish_time'] = $currentDateTime->toTimeString();
					$logData['checkout'] = 1;
					break;
			}

			// Insert log data into the database
			DB::table('technician_logs')->insert($logData);

			if ($request->input('status') == 'Rejected') {
				$workOrder = WorkOrder::findOrFail($request->input('workorder'));
				$workOrder->status = "Rejected";
				$workOrder->technician = null;
				$workOrder->allocation_status = 0;
				$workOrder->save();

				// $workOrder->update([
				// 	'status' => $request->input('status'),
				// 	'technician' => null,
				// 	'allocation_status' => 0
				// ]);

				return redirect()->route('home')->with('success', __('Technician removed successfully.'));
			} else {
				return redirect()->back()->with('success', 'Work order status updated successfully!');
			}
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to update status. Please try again.');
		}
	}
	public function removeTechnician(Request $request)
	{
		$request->validate([
			'workorder_id' => 'required|exists:work_orders,id',
			'technician_id' => 'required|exists:users,id',
		]);

		try {
			$workOrder = WorkOrder::findOrFail($request->workorder_id);
			$technicianId = $request->technician_id;
			$technicians = $workOrder->technician ? json_decode($workOrder->technician, true) : [];

			// If the technicians array is empty or not found
			if (empty($technicians)) {
				return redirect()->back()->with('error', 'No technicians are allocated to this work order.');
			}

			// Check if the technician exists in the array
			if (($key = array_search($technicianId, $technicians)) !== false) {
				// Remove the technician from the array
				unset($technicians[$key]);

				// Re-encode the array and update the work order
				$workOrder->technician = json_encode(array_values($technicians)); // Reindex the array

				// If no technicians remain, update allocation status and workorder status
				if (empty($technicians)) {
					$workOrder->allocation_status = 0; // Reset allocation status
					$workOrder->status = 'Open'; // Update status to Open
				}

				$workOrder->save();
				$appointment = TechnicianBookingAppointment::where('workorder_id', $request->workorder_id)->where('technician_id', $technicianId)->first();
				if ($appointment) {
					$appointment->delete();
				}
				return redirect()->back()->with('success', 'Technician removed from allocation successfully!');
			} else {
				return redirect()->back()->with('error', 'Technician not found in the allocation.');
			}
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Failed to remove technician. Please try again.');
		}
	}

	public function addwarranty(Request $request)
	{
		$existingWarrantyItem = AddWarrantyItems::where('workorderid', $request->workorder_id)
			->where('booking_item_id',  $request->booking_item_id)
			->first();
		return view('techdashboard.addwarranty', [
			'product_id' => $request->product_id,
			'workorder_id' => $request->workorder_id,
			'booking_item_id' => $request->booking_item_id,
			'period' => $request->warranty_period,
			'warrant_number' => $existingWarrantyItem->warrantynumber ?? null
		]);
	}

	public function getinvoice(Request $request)
	{
		$workorder = WorkOrder::findOrFail($request->workorder);
		$invoice_id = json_decode($workorder->invoice, true);
		$first = $invoice_id[0] ?? null;
		$invoice = Invoice::find($first);
		if ($invoice) {
			return response()->json([
				'message' => 'success',
				'data' => $invoice
			]);
		} else {
			return response()->json([
				'message' => 'fail',
				'data' => null
			]);
		}
	}

	public function vehiclesDetailsEdit(Request $request)
	{
		$vehicles = Vehicle::where('id', $request->vehicle_id)->update([
			"rego" => $request->rego,
			"v_make" => $request->v_make,
			"vm" => $request->v_model,
			"model_series" => $request->model_series,
			"vin" => $request->vin,
			"Odometer" => $request->Odometer
		]);

		return response()->json([
			'status' => 'success',
			'message' => 'Vehicle details updated successfully!'
		]);
	}

	public function bookingDetailsEdit(Request $request)
	{
		$vehicles = User::where('id', $request->cientId)->update([
			"first_name" => $request->first_name,
			"last_name" => $request->last_name,
			"ccp" => $request->m_cc,
			"phone_number" => $request->phone_number,
		]);

		$booking = Booking::where('id', $request->bookingId)->update([
			"requested_date" => $request->requestdate,
			"requested_time" => $request->requesttime,
			"city" => $request->city,
			"service_location" => $request->service_location,
			"description" => $request->description,
			"landmark" => $request->landmark
		]);

		return response()->json([
			'status' => 'success',
			'message' => 'Booking details updated successfully!'
		]);
	}

	public function workOrderImage(Request $request)
	{
		$request->validate([
			'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);

		$total = WorkOrderImg::where('order_id', $request->order_id)->count();
		if ($total >= 6) {
			return back()->with('success', 'Images can upload only six!');
		} else {
			$path = "assets/workOrder/";
			if ($request->hasFile('image')) {
				foreach ($request->file('image') as $file) {
					$total = WorkOrderImg::where('order_id', $request->order_id)->count();
					if ($total >= 6) {
						return back()->with('success', 'Images can upload only six!');
					} else {
						$fullName =  time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
						$file->move(public_path($path), $fullName);
						$fileLink = $path . $fullName;

						WorkOrderImg::create([
							'order_id' => $request->order_id,
							'image' => $fileLink
						]);
					}
				}
			}
			return back()->with('success', 'Images uploaded successfully!');
		}
	}

	public function workOrderImageDelete($id)
	{
		$item = WorkOrderImg::findOrFail($id);

		($item->image != null) ? (file_exists($item->image) ? unlink($item->image) : '') : '';
		$item->delete();

		return redirect()->back()->with('success', 'Image deleted successfully!');
	}

	public function workorderscrap($id, $source)
	{

		$scrap = ScrapModule::first();
		$source = $source;
		$workorder = $id;
		return view('workorder.scrap', compact('source', 'scrap', 'workorder'));
	}

	public function saveworkorderscrap(Request $request)
	{
		//dd($request->all());
		if ($request->workorder) {
			return response()->json([
				'status' => 404,
				'message' => 'Work order is s missing',

			]);
		}
		$products = $request->products;
		foreach ($products as $product) {
			$wo_scrap = new Workorder_scrap();
			$wo_scrap->wo_id = $request->workorder_id;
			$wo_scrap->scrap_name = $product['product_name'];
			$wo_scrap->scrap_id = $product['product_id'];
			$wo_scrap->qty = $product['qty'];
			$wo_scrap->save();
		}
		return response()->json([
			'status' => 200,
			'message' => 'Scrap items saved successfully!',
			'workorder_id' => $request->workorder_id,
			'source' => $request->source
		]);
	}
	public function deletescrap($id)
	{
		$wo_scrap = Workorder_scrap::find($id);
		//($wo_scrap);
		if ($wo_scrap) {
			$wo_scrap->delete();
			return redirect()->back()->with('success', 'scrap deleted successfully!');
		}
		return redirect()->back()->with('error', 'scrap not Found');
	}
}
