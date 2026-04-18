<?php

namespace App\Http\Controllers;


use App\Models\WorkOrder;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;
use App\Mail\Recieptemail;
use Illuminate\Support\Facades\Mail;
use PDF;

class InvoicePaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('payment.index', compact('payments'));
    }
    public function create()
    {
        
        $clients = User::where('parent_id', parentId())
            ->where('type', 'client')
            ->get()
            ->pluck('first_name', 'id');
    
      
         $workorders = WorkOrder::where('status','invoiced')->get();
        if ($workorders->isEmpty()) {
            return redirect()->back()->with('error', 'Work order not found.');
        }
    
    
        $invoiceIds =Invoice::all()->pluck('id')->toArray()    ?? [];
        
       
        $firstInvoiceId = $invoiceIds[0] ?? null;
     
     
         $invoice = $firstInvoiceId ? Invoice::find($firstInvoiceId) : null;
        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }
    
       
        // $vehicleIds = json_decode($workOrder->vehicle, true) ?? [];
        // $bookingIds = json_decode($workOrder->booking, true) ?? [];
    
     
       // $vehicles = Vehicle::whereIn('id', $vehicleIds)->get();
    
       
       // $clientDetails = $workOrder->client;
    
       
      //  $settings = settings();
    
       
        return view('payment.create',compact('workorders','invoice'));

    }



    public function store(Request $request)
{
   

    $validator = \Validator::make(
        $request->all(),
        [
            'client' => 'required|string|max:255',
            'parent_id' => 'required|string|max:255',
            'payment_date' => 'required|string|max:255',
        ]
    );

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

   
    $invoiceId = $request->invoice[0] ?? null;

   
    $workorderId = null;
    if ($invoiceId) {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $workorderId = $invoice->wo_id;
        }
    }

    
    $payment = new Payment();
    $payment->parent_id = $request->parent_id;
    $payment->payment_date = $request->payment_date;
    $payment->client = $request->client;
    $payment->invoice = json_encode($request->invoice);
    $payment->payment_method = $request->payment_method;
    $payment->paid_amount = $request->paid_amount;
    $payment->status = $request->status;
    $payment->description = $request->description;
    $payment->workorder = $workorderId; 
    $payment->save();

    
    if ($workorderId) {
        $workOrder = WorkOrder::find($workorderId);
        if ($workOrder) {
            $existingPayments = json_decode($workOrder->payment, true) ?? [];
            $existingPayments[] = $payment->id;
            $workOrder->payment = json_encode(array_unique($existingPayments));
            $workOrder->save();
        }
        return redirect()->back()->with('success','Payment created successfully');
    }

    return redirect()->back()->with('success', __('Payment successfully created.'));
}
  // In FileAccessController.php
  public function download($id)
  {
          // filename should be a relative path inside storage/app to your file like 'userfiles/report1253.pdf'
         $payment=Payment::findOrFail($id);
         if($payment->image_path)
         {

          return Storage::download($payment->image_path);
         }
      
  }
  public function sendemail($id){
    $payments = Payment::findOrFail($id);
   if($payments->user->email)
    {
        $clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('full_name', 'id');
       //$payments = Payment::findOrFail($id);
        $invoice=$payments->invoices;
       // $invoices = Invoice::all();
       //dd($payments);
        $settings=settings();
         Mail::to($payments->user->email)->send(new Recieptemail($clients,$payments,$settings,$invoice));
    
         return redirect()->back()->with('success', __('Email sent successfuly!'));
    }
    else 
    return redirect()->back()->with('error', __('Customer Email not found'));
}

public function storepayment(Request $request)
{
   // dd($request->all());
    $totalPaidAmount = 0;
    $invoiceIds = [];
    $paymentMethods = [];
    $paidAmounts = [];
    $paymentReference=[];
    $invoices = json_decode($request->invoices, true);
    $paymentMethodsAll = json_decode($request->paymentMethods, true);

    // Loop through invoices and prepare the data
    foreach ($invoices as $invoiceData) {
        $invoiceIds[] = $invoiceData['id'];
        $totalPaidAmount += $invoiceData['appliedAmount'];

        // Find each invoice and update its status to 'Paid'
        $invoice = Invoice::find($invoiceData['id']);
        if ($invoice) {
            $invoice->status = 'Paid';
            $invoice->save();
        }
    }
    
    // Prepare payment methods and amounts
    foreach ($paymentMethodsAll as $paymentMethodData) {
        $paymentMethods[] = $paymentMethodData['method'];
        $paidAmounts[] = $paymentMethodData['amount'];
        $paymentReference[] = $paymentMethodData['reference'];
    }
//dd($paidAmounts);
    // Concatenate payment methods and amounts into comma-separated values
    $paymentMethodString = implode(',', $paymentMethods);
    $paidAmountString = implode(',', $paidAmounts);
    $paymentReferenceString=implode(',', $paymentReference);
    $workOrderId = $request->workOrderId;

    // Find the work order
    $workOrder = WorkOrder::find($workOrderId);

    //dd($workOrder->id);    // Create new payment record
    $payment = new Payment();
    $payment->parent_id = $request->parent_id;
    $payment->payment_date = now();
    $payment->client = $workOrder->customer_id;
    $payment->invoice = implode(',', $invoiceIds);
    $payment->payment_method = $paymentMethodString; // Store methods as comma-separated
    $payment->paid_amount = $paidAmountString; // Store amounts as comma-separated
    $payment->payment_reference = $paymentReferenceString;
    $payment->status = "paid";
 
    $payment->workorder = $workOrderId;
    $payment->description = "Booking";
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $url=  Storage::putFileAs('upload/payment', $file, $filename);
        $payment->image_path = $url;
    }
    $payment->save();

    // Update the work order with the new payment ID
    if ($workOrder) {
        $paymentids = json_decode($workOrder->payment, true) ?? [];
        $paymentids[] = $payment->id;
        $workOrder->payment = json_encode($paymentids);
		$workOrder->status = 'Paid';

        $workOrder->save();
    }
// dd($request->source);
    // Return success response
    return response()->json([
        'success' => true,
        'message' => __('Payment successfully created.'),
        'invoiceId' => $invoiceIds ? $invoiceIds[0] : null,
        'paymentId'=>$payment->id,
        'workorderId'=>$workOrderId,
        'source'=>$request->source
    ]);
    }

    
    
    public function edit($id)
    {

        $payments = Payment::findOrFail($id);
        $invoices = Invoice::get(['id', 'total', 'discount']);
        $clientIds = Invoice::pluck('client')->toArray();

      
        $clients = User::where('parent_id', parentId())
            ->where('type', 'client')
            ->whereIn('id', $clientIds)
            ->get()
            ->pluck('full_name', 'id');
            $selectedInvoiceIds = json_decode($payments->invoice, true) ?? []; // IDs from the payment record

        return view('payment.edit', compact('payments', 'clients', 'invoices','selectedInvoiceIds'));
    }


    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'client' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $payment = Payment::findOrFail($id);
        $payment->parent_id = $request->parent_id;
        $payment->payment_date = $request->payment_date;
        $payment->client = $request->client;
        $payment->invoice = $request->invoice;
        $payment->payment_method = $request->payment_method;
        $payment->paid_amount = $request->paid_amount;
        $payment->status = $request->status;
        $payment->description = $request->description;
        $payment->save();

        return redirect()->back()->with('success', __('Payments successfully updated.'));
    }


    public function destroy($id)
    {
        $payments = Payment::find($id);
        $payments->delete();
        return redirect()->back()->with('success', 'Note successfully deleted.');
    }


  
    public function show($id)
    {
        $clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('full_name', 'id');
        $payments = Payment::findOrFail($id);
        $invoice=$payments->invoices;
        $settings=settings();
        return view('payment.show', compact('payments', 'clients', 'settings','invoice'));
    }
    public function pdfview($id)

    { 
        //dd($id);
        //$clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('full_name', 'id');
        $payments = Payment::findOrFail($id);
       // dd($payments)
       // $invoices = Invoice::all();
        $settings=settings();
       
         $invoice=$payments->invoices;
         //dd($invoice);


            $pdf = PDF::loadView('payment.show',['payments' => $payments,'settings'=>$settings,'invoice'=>$invoice]);
          //  dd($pdf);

            return $pdf->download('payment.show.pdf');

        



        return view('pdfview');

    }


    public function getInvoicesByClient(Request $request)
    {
        $clientId = $request->input('client_id');
        $invoices = Invoice::where('client', $clientId)->get(['id', 'invoice_id', 'total', 'discount']); // Adjust fields if necessary

        return response()->json($invoices);
    }
}