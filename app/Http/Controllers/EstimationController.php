<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\BookingItems;
use App\Models\Estimation;
use App\Models\ServicePart;
use App\Mail\QuotationEmail;
use Illuminate\Http\Request;
use App\Models\BookingQuotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\EstimationServicePart;
use Illuminate\Support\Facades\Crypt;

class EstimationController extends Controller
{

    public function index()
    {
            $estimations = BookingQuotation::withSum('items','totalamount')->get();
           
            return view('estimation.index', compact('estimations'));
       
    }


    public function create()
    {
      
            $clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('name', 'id');
            $clients->prepend(__('Select Client'), '');

            $parts = ServicePart::where('parent_id', parentId())->get()->pluck('title', 'id');
            $parts->prepend(__('Part & Service'), '');

            $estimationNumber = $this->estimationNumber();
            return view('estimation.create', compact('clients', 'estimationNumber', 'parts'));
       
    }


    public function store(Request $request, $slug = false)
    {

     
            $validator = \Validator::make(
                $request->all(),
                [
                    'estimation_id' => 'required',
                    'client' => 'required',
                    'due_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $estimation                 = new Estimation();
            $estimation->estimation_id  = $request->estimation_id;
            $estimation->title          = $request->title;
            $estimation->client         = $request->client;
            $estimation->due_date       = $request->due_date;
            $estimation->notes          = $request->notes;
            $estimation->status         = 'pending';
            $estimation->parent_id      = parentId();
            $estimation->save();

            $parts = !empty($request->parts) ? $request->parts : [];

            if (!empty($parts)) {
                for ($i = 0; $i < count($parts); $i++) {
                    $service = ServicePart::find($parts[$i]['service_part_id']);
                    $estimationPart = new EstimationServicePart();
                    $estimationPart->estimation_id          = $estimation->id;
                    $estimationPart->service_part_id        = $parts[$i]['service_part_id'];
                    $estimationPart->quantity               = $parts[$i]['quantity'];
                    $estimationPart->amount                 = $parts[$i]['amount'];
                    $estimationPart->description            = $parts[$i]['description'];
                    $estimationPart->type                   = $service->type ?? 'part';
                    $estimationPart->save();
                }
            }
           
            if ($slug) {
                return $estimation->id;
            } else {
                return redirect()->route('estimation.index')->with('success', __('Estimation successfully created.'));
            }
     
    }


    public function show($id)
{
    $id = Crypt::decrypt($id);
      
    
      
        $quotation = BookingQuotation::findOrFail($id);
    
       
        $client = User::with('clients')->findOrFail($quotation->customer_id);
         $booking = Booking::findOrFail($quotation->booking_id);
      
        $vehicle = Vehicle::with('vehicle_models')->findOrFail($booking->vehicle);
    
        $products = BookingItems::where('quotation_id', $quotation->id)->get();
  
    //$estimation = BookingQuotation::with('customer', 'items')->findOrFail($id);


   
    return view('estimation.show', compact('quotation','client','vehicle','products','booking'));
}


    public function edit($id)
    {
            $estimation = Estimation::find($id);

            $clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('name', 'id');
            $clients->prepend(__('Select Client'), '');

            $parts = ServicePart::where('parent_id', parentId())->get()->pluck('title', 'id');
            $parts->prepend(__('Select Part & Service'), '');

            $estimationPartData = $estimation->serviceParts;
            $estimationParts = [];
            foreach ($estimationPartData as $estimationPart) {
                $estimationPart['id'] = $estimationPart->id;
                $estimationPart['estimation_id'] = $estimationPart->estimation_id;
                $estimationPart['service_part_id'] = $estimationPart->service_part_id;
                $estimationPart['quantity'] = $estimationPart->quantity;
                $estimationPart['amount'] = $estimationPart->amount;
                $estimationPart['unit'] = !empty($estimationPart->serviceParts) ? $estimationPart->serviceParts->unit : '';
                $estimationPart['description'] = !empty($estimationPart->serviceParts) ? $estimationPart->serviceParts->description : '';
                $estimationParts[] = $estimationPart;
            }

            return view('estimation.edit', compact('clients', 'estimation', 'parts', 'estimationParts'));
       
    }


    public function update(Request $request, $id, $slug = false)
    {
      
            $validator = \Validator::make(
                $request->all(),
                [
                    'estimation_id' => 'required',
                    'client' => 'required',
                    'due_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $id = Crypt::decrypt($id);
            $estimation = Estimation::find($id);

            $estimation->estimation_id = $request->estimation_id;
            $estimation->title = $request->title;
            $estimation->client = $request->client;
            $estimation->due_date = $request->due_date;
            $estimation->notes = $request->notes;
            $estimation->save();
            // $services = !empty($request->services) ? $request->services : [];
            $parts = !empty($request->parts) ? $request->parts : [];

            // for ($i = 0; $i < count($services); $i++) {
            //     $estimationService = EstimationServicePart::find(isset($services[$i]['id']) ? $services[$i]['id'] : 0);
            //     if ($estimationService == null) {
            //         $estimationService = new EstimationServicePart();
            //         $estimationService->estimation_id = $estimation->id;
            //     }
            //     $estimationService->service_part_id = $services[$i]['service_part_id'];
            //     $estimationService->quantity = $services[$i]['quantity'];
            //     $estimationService->amount = $services[$i]['amount'];
            //     $estimationService->description = $services[$i]['description'];
            //     $estimationService->type = 'service';
            //     $estimationService->save();
            // }


            for ($i = 0; $i < count($parts); $i++) {
                $estimationPart = EstimationServicePart::find(isset($parts[$i]['id']) ? $parts[$i]['id'] : 0);
                $service = ServicePart::find($parts[$i]['service_part_id']);
                if ($estimationPart == null) {
                    $estimationPart = new EstimationServicePart();
                    $estimationPart->estimation_id = $estimation->id;
                }
                $estimationPart->service_part_id = $parts[$i]['service_part_id'];
                $estimationPart->quantity = $parts[$i]['quantity'];
                $estimationPart->amount = $parts[$i]['amount'];
                $estimationPart->description = $parts[$i]['description'];
                $estimationPart->type = $service->type ?? 'part';
                $estimationPart->save();
            }
            if ($slug) {
                return $estimation->id;
            } else {
                return redirect()->route('estimation.index')->with('success', __('Estimation successfully updated.'));
            }
      
    }


    public function destroy($id)
    {
        try {
           
            $estimation = BookingQuotation::findOrFail($id);
    
            // Delete related booking items
            $estimation->items()->delete();
    
            // Delete the booking quotation
            $estimation->delete();
    
            return redirect()->route('estimation.index')->with('success', 'Quotation and items deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting quotation: ' . $e->getMessage());
        }
    }
    

    public function estimationNumber()
    {
        $lastEstimation = Estimation::where('parent_id', parentId())->latest()->first();
        if ($lastEstimation == null) {
            return 1;
        } else {
            return $lastEstimation->estimation_id + 1;
        }
    }

    public function getServicePart(Request $request)
    {
        $servicePart = ServicePart::find($request->id);
        return response()->json($servicePart);
    }

    public function servicePartDestroy(Request $request)
    {
      
            if (isset($request->id) && !empty($request->id)) {
                $servicePart = EstimationServicePart::find($request->id);
                $servicePart->delete();
            }
            return 1;
       
    }

    public function estimationStatus(Request $request, $estimationId)
    {
        $estimation = Estimation::find($estimationId);
        $estimation->status = $request->status;
        $estimation->save();
        return redirect()->back()->with('success', __('Estimation status successfully changed.'));
    }

	public function estimationEmail($id)
    {		
		
		$estimation = BookingQuotation::findOrFail($id);
		$user = User::find($estimation->customer_id);
		$details = [
			'to' => $user->email,
			'from' => Auth::user()->email,
			'from_name' => 'Dial A Battery',
			'subject' => 'Dial A Battery - Quotation:QUT00'.$estimation->id,
			'heading' => 'Dial A Battery - Quotation:QUT00'.$estimation->id,
			'body' => 'Thank you for your interest in our vehicle service.Please find attached the quotation for your requested service The attached PDF includes full details, pricing, and terms.If you have any questions or would like to proceed, please feel free to contact us. We look forward to assisting you.',
	        'name' => $user->first_name,
			'amount' => $estimation->items?->sum('totalamount'),
			'footer' => 'If you have any questions, feel free to contact our support team.',
		];
		Mail::send(new QuotationEmail($details, $estimation));
		// Mail::to($email)->send(new QuotationEmail($details));
        return redirect()->back()->with('success', __('Quatation send successfully'));
    }
}
