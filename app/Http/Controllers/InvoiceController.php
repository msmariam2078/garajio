<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\User;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Vehicle;
use App\Mail\InvoiceMail;
use App\Models\Warehouse;
use App\Models\WorkOrder;
use App\Models\ServicePart;
use Illuminate\Http\Request;
use App\Models\WOServicePart;
use App\Models\Inventory_setup;
use App\Models\InventoryDetail;
use App\Models\Workorder_scrap;
use App\Models\AddWarrantyItems;
use App\Models\BookingQuotation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\ServicePartadjustMent;
use App\Models\Warranty_registration;
use Illuminate\Support\Facades\Crypt;

class InvoiceController extends Controller
{

	public function index()
	{
		$invoices = Invoice::get();
		$clients = User::where('parent_id', parentId())->where('type', 'client')->get();
		$invoiceNumber = $this->invoiceNumber();
		$status = Invoice::$status;
		return view('invoice.index', compact('invoices', 'clients', 'invoiceNumber', 'status'));
	}


	public function create()
	{
		$invoiceNumber = $this->invoiceNumber();
		$status = Invoice::$status;
		return view('invoice.create', compact('clients', 'invoiceNumber', 'status'));
	}
	public function newcreate($workorder, $customer)
	{
		$settings = settings();
		$latestBookingId = Invoice::latest('id')->value('id');
		$nextBookingId = $latestBookingId ? $latestBookingId + 1 : 1;


		$bookingReference = $settings['invoice_number_prefix'] . $nextBookingId;

		return view('invoice.newcreate', compact('workorder', 'customer', 'bookingReference'));
	}


	public function store(Request $request)

	{
		//dd($request->status);

		if (\Auth::user()->can('create invoice')) {
			$validator = \Validator::make(
				$request->all(),
				[
					'client' => 'required',
					'workorder' => '',
					'invoice_date' => 'required',
					'due_date' => 'required',
					'total' => 'required',
					'status' => 'required',
				]
			);
			if ($validator->fails()) {
				$messages = $validator->getMessageBag();
				return redirect()->back()->with('error', $messages->first())->withInput();
			}

			$invoice = new Invoice();
			$invoice->client = $request->client;
			//  $invoice->wo_id = 15;
			$invoice->invoice_id = $request->invoice_id;
			$invoice->invoice_date = $request->invoice_date;
			$invoice->due_date = $request->due_date;
			$invoice->total = $request->total;
			$invoice->final_amount = $request->final_amount;
			$invoice->discount = !empty($request->discount) ? $request->discount : 0;
			$invoice->status = $request->status;
			$invoice->notes = !empty($request->notes) ? $request->notes : null;
			$invoice->parent_id = parentId();
			$invoice->save();
			// dd($invoice);


			if ($request->workorder_id) {
				$woids = $request->workorder_id;
				$workOrders = WorkOrder::where('id', $woids)->get();
				foreach ($workOrders as $workOrder) {
					$existingVehicles = json_decode($workOrder->invoice, true);
					$existingVehicles = $existingVehicles ?? [];
					$existingVehicles[] = $invoice->id;
					$uniqueVehicles = array_unique($existingVehicles);
					$workOrder->invoice = json_encode($uniqueVehicles);
					$workOrder->save();
				}



				return redirect()->route('workorder.edit', ['workorder' => $request->workorder_id]);
			}

			return redirect()->back()->with('success', __('Invoice successfully created.'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function invoicestore(Request $request)
	{

		if (\Auth::user()->can('create invoice')) {
			$invoice = new Invoice();
			$invoice->client = $request->customerId;
			$invoice->wo_id = 1;
			$invoice->invoice_id = $request->invoiceNo;
			$invoice->invoice_date = $request->postDate;
			$invoice->due_date = $request->due_date;

			$invoice->total = floatval(str_replace('$', '', $request->total));
			$invoice->discount = !empty($request->discount) ? floatval(str_replace('$', '', $request->discount)) : 0;
			$invoice->final_amount = floatval(str_replace('$', '', $request->total)) - $invoice->discount;

			$invoice->status = $request->status;
			$invoice->notes = !empty($request->notes) ? $request->notes : null;
			$invoice->parent_id = parentId();
			$invoice->post_date = $request->postDate;
			$invoice->followup_date = $request->followupDate;
			$invoice->invoice_type = $request->invoiceType;
			$invoice->account_type = $request->status === '1' ? 1 : 0;
			$invoice->odometer = $request->odometer;
			$invoice->hours = $request->hours;
			$invoice->next_service_kms = $request->nextservice;
			$invoice->job_status_comment = $request->jobstatuscmt;
			$invoice->customer_source = $request->customersource;
			$invoice->payment_terms = $request->payment_terms;
			$invoice->description = $request->description;

			$invoice->save();


			if ($request->newcreate === '1') {
				return redirect()->back()->with("success", "Invoice created successfully");
			} else {
				return response()->json(['success' => true, 'message' => __('Invoice successfully created.')]);
			}
		} else {
			return response()->json(['success' => false, 'message' => __('Permission Denied.')]);
		}
	}


	public function show($id)
	{

		if (\Auth::user()->can('show work order')) {

			$invoice = Invoice::findOrFail($id);
			$workorder = WorkOrder::find($invoice->wo_id) ?? null;
			//    dd($workorder);
			$vehicleId = json_decode($workorder?->vehicle, true) ?? [];
			$techId = json_decode($workorder?->technician, true) ?? [];
			$vehicles = Vehicle::whereIn('id', $vehicleId)->get() ?? [];
			$technician = User::whereIn('id', $techId)->get() ?? [];
			$bookingIds = json_decode($workorder?->booking, true) ?? [];
			$quotations = [];
			$bookings = Booking::where('id', $bookingIds)->first();
			//dd($technician);
			if ($workorder) {
				$quotations = DB::table('booking_quotations')
					->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
					->where('booking_quotations.booking_id', $bookings?->id)
					->where('booking_quotations.status', 'confirmed')
					->select(
						'booking_quotations.id as quotation_id',
						'booking_quotations.send_date',
						'booking_quotations.status',
						'booking_items.id',
						'booking_items.product_id',
						'booking_items.item_no',
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
				foreach ($quotations as $quotation_id => $quotation) {
					foreach ($quotation as $item) {

						$existingWarrantyItem = AddWarrantyItems::where('workorderid', $workorder->id)
							->where('booking_item_id', $item->id)->first();


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
			}
			$salesModules = $invoice->sales ?? [];

			return view('invoice.show', compact('invoice', 'workorder', 'vehicles', 'technician', 'quotations', 'salesModules'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied!'));
		}
	}


	public function sendInvoiceEmail($id)
	{
		$invoice = Invoice::findOrFail($id);
		$client = User::findOrFail($invoice->client);
		$workorder = WorkOrder::find($invoice->wo_id);
		$vehicleId = json_decode($workorder->vehicle, true) ?? [];
		$techId = json_decode($workorder->technician, true) ?? [];
		$vehicles = Vehicle::whereIn('id', $vehicleId)->get() ?? [];
		$technicians = User::whereIn('id', $techId)->pluck('first_name') ?? [];
		$bookingIds = json_decode($workorder->booking, true) ?? [];
		$technician = User::whereIn('id', $techId)->get() ?? [];
		$vehicle = !$vehicles->isEmpty() ? $vehicles[0] : null;
		$bookings = Booking::where('id', $bookingIds)->first();
		if ($workorder) {
			$quotations = DB::table('booking_quotations')
				->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
				->where('booking_quotations.booking_id', $bookings->id)
				->where('booking_quotations.status', 'confirmed')
				->select(
					'booking_quotations.id as quotation_id',
					'booking_quotations.send_date',
					'booking_quotations.status',
					'booking_items.id',
					'booking_items.product_id',
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
			foreach ($quotations as $quotation_id => $quotation) {
				foreach ($quotation as $item) {
					$existingWarrantyItem = AddWarrantyItems::where('workorderid', $workorder->id)
						->where('booking_item_id', $item->id)->first();


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
		}

		if (!$client) {
			return response()->json(['error' => 'Client not found'], 404);
		}



		$details = [
			'to' => $client->email,
			'from' => Auth::user()->email,
			'from_name' => 'Dial A Battery',
			'subject' => 'Dial A Battery - Invoice:INV00' . $invoice->id,
			'heading' => 'Dial A Battery - Invoice:INV00' . $invoice->id,
			'body' => 'Thank you for choosing Dial A Battery! We have attached the invoice for the service provided. You will find all the details in the attached PDF. If you have any questions about the invoice or need further assistance, feel free to reach out to us at 800247365.',
			'user_name' => $client->first_name,
			'footer' => 'Thank you for your business!',
		];

		// Send the email with the PDF file link
		Mail::send(new InvoiceMail($invoice, $details, $quotations, $vehicles, $workorder, $technician));

		return response()->json(['success' => true, 'message' => 'Invoice sent via email']);
	}

	public function generateInvoicePDF($id)
	{ //dd(1);
		// Fetch the necessary data
		$invoice = Invoice::find($id);
		$workorder = WorkOrder::find($invoice->wo_id);
		$vehicleId = json_decode($workorder->vehicle, true) ?? [];
		$techId = json_decode($workorder->technician, true) ?? [];
		$vehicles = Vehicle::whereIn('id', $vehicleId)->get() ?? [];
		$technicians = User::whereIn('id', $techId)->pluck('first_name') ?? [];
		$bookingIds = json_decode($workorder->booking, true) ?? [];
		$technician = !$technicians->isEmpty() ? $technicians[0] : '';
		$vehicle = !$vehicles->isEmpty() ? $vehicles[0] : '';
		$bookings = Booking::where('id', $bookingIds)->first();

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

		// Prepare the HTML content for the PDF
		$html = '<!DOCTYPE html>
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
						<h2 style="text-align:right;">Tax Invoice</h2>   
						<div style="text-align:right;">Invoice Date :' . optional($invoice->invoice_date)->format('F j, Y') . '</div>
						<div style="text-align:right;">Invoice No :INV00' . $invoice->id . '</div>
					
						<div style="text-align:right;">Page No : 1-1</div>
						</div>
					</header>


					<section class="details-section" style="position:relative;height:20%">

						<div class="details-box" style="position:absolute;left:0">
						<p><strong>Customer:</strong>' . $workorder->client->full_nam . '</p>
							<p><strong>Addresss:</strong> ' . $workorder->client?->clients?->service_address

						. ' <br>' . $workorder->client?->clients?->service_city .
						',' . $workorder->client?->clients?->service_state .
						',' . $workorder->client?->clients?->service_country .
						',' . $workorder->client?->clients?->service_zip_code . '
											</p>
							<p><strong>Tel No:</strong> ' . preg_replace('/[^0-9]/', '',  $workorder->client->ccm) . $workorder->client?->phone_number . '</p>
							<p><strong>Email:</strong>  ' . $workorder->client?->email . '</p>
							
						</div>


						<div class="details-box" style="position:absolute;right:0">';

					foreach ($vehicles as $key => $vehicle) {
						$html .= '<p><strong>Registation No:</strong>' .  $vehicle->rego  . '.</p>
							<p><strong>Make::</strong> ' .  $vehicle->vehicle_makes?->make_name  . '</p>
							<p><strong>Model:</strong>   ' . $vehicle->vehicle_models?->model_name . '</p>
							<p><strong>Year:</strong>   ' . $vehicle->model_series . '</p>';
					}
					$html .= '
							<p><strong>Technician:' . $technician . '</strong> </p>
					
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

					$subtotal = 0;
					$discount = 0;
					$tax = 0;

					foreach ($quotations as $quotation) {
						foreach ($quotation as $item) {
							$html .= '<tr>
																
																	<td> ' . $item->product_id . '</td>
																	<td> ' . $item->product_name . '</td>
																	<td> ' . $item->warrenty . ' </td>
																	<td>' . $item->warrenty . ' </td>
																	<td> ' . $item->qty . ' </td>
																	<td> ' . $item->unit_price . '</td>
																
																	<td> ' . $item->gstprice . '</td>
																	<td> ' . $item->linetotal . '</td>
																
																	<td> ' . $item->taxpercentage . '</td>
																	<td>' . $item->taxamount . '</td>
																	<td>' . $item->totalamount . '</td>
																</tr>';
							$dis_int = (int) filter_var($item->gstprice, FILTER_SANITIZE_NUMBER_INT) ?? 0;
							$unit_price = (doubleval($item->unit_price) ?? 0) * (int)($item->qty ?? 1);
							$discount = $discount + ($dis_int / 100) * $unit_price;

							$subtotal = $subtotal + $unit_price;

							$tax = $tax + $item->taxamount;
						}
					}
					$duetotal = $subtotal + $tax;
					$html .= '    </tbody>
					</table>
			</section>
					<!-- Total Section -->
					<section class="total-section text-right" style="text-align:right">
						
					
											<p>Sub total :' . $subtotal . '</p>
											<p>Tax:' . $tax . '</p>
											<p>Discount :' . $discount . '</p>
											<p>Total Due: <b>' . $duetotal . '</b></p>
											
									
					</section>


					<!-- Notes Section -->
					<section>
					<h3 style="font-weight:bold">Terms & Conditions:</h3><br>
						<ol>
							<li>The original warranty card is required to claim the warranty.</li>
							<li>A non-refundable site visit fee of AED 105.00 (incl. VAT) applies,irrespective of whether any service is availed.</li>
							<li>Warranty can be claimed only once during the warranty period, for terms and conditions refer warranty card.</li>
							<li>Dial A Battery is not responsible for damage to goods after installation.</li>
							<li>The electronic document sent by email will be considered the original.</li>
							<li>We are not responsible for the loss of any personal items or belongings, these must be secured by the car owner / customer.</li>
						</ol>
					</section>

					<!-- Footer Section -->
					<footer class="footer">
						<p>I hereby confirm receipt of the goods in good condition and agree to dispose of the old battery in compliance with UAE regulations through authorized channels, ensuring proper handling and recycling.</p>

					</footer>


				</div>
			</body>';

		// Generate PDF using Dompdf
		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isPhpEnabled', true);

		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);

		// Set paper size
		$dompdf->setPaper('A4', 'portrait');

		// Render PDF
		$dompdf->render();

		// Define output path
		$pdfOutputPath = public_path('invoices/invoice_' . $id . '.pdf');

		// Save PDF file
		file_put_contents($pdfOutputPath, $dompdf->output());

		// Return the file URL in the response
		return response()->json([
			'success' => true,
			'file_url' => asset('invoices/invoice_' . $id . '.pdf')
		]);
	}

	public function print($id)
	{
			$invoice = Invoice::findOrFail($id);
			$workorder = WorkOrder::find($invoice->wo_id) ?? null;

			$vehicleId = json_decode($workorder?->vehicle, true) ?? [];

			$paymentID = json_decode($workorder?->payment, true) ?? [];
			$payment = Payment::where('id', $paymentID)->first();

			$techId = json_decode($workorder?->technician, true) ?? [];
			$vehicle = Vehicle::whereIn('id', $vehicleId)->first();

			$technician = User::whereIn('id', $techId)->first();
			$bookingIds = json_decode($workorder?->booking, true) ?? [];
			$quotations = [];
			$bookings = Booking::where('id', $bookingIds)->first();

			if ($workorder) {
				$quotations = DB::table('booking_quotations')
					->join('booking_items', 'booking_quotations.id', '=', 'booking_items.quotation_id')
					->where('booking_quotations.booking_id', $bookings?->id)
					->where('booking_quotations.status', 'confirmed')
					->select(
						'booking_quotations.id as quotation_id',
						'booking_quotations.send_date',
						'booking_quotations.status',
						'booking_items.id',
						'booking_items.product_id',
						'booking_items.item_no',
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
				foreach ($quotations as $quotation_id => $quotation) {
					foreach ($quotation as $item) {

						$existingWarrantyItem = AddWarrantyItems::where('workorderid', $workorder->id)
							->where('booking_item_id', $item->id)->first();


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
			}

			$salesModules = $invoice->sales ?? [];
			$setting = Setting::pluck('value', 'name');

			$data = [
				'invoice' => $invoice,
				'workorder' => $workorder,
				'vehicle' => $vehicle,
				'technician' => $technician,
				'quotations' => $quotations,
				'salesModules' => $salesModules,
				'setting' => $setting,
				'payment' => $payment,
			];
			$pdf = PDF::loadView('invoice.show-new', $data); // 'pdf.myview' refers to resources/views/pdf/myview.blade.php
            return $pdf->download('Invoice-('.$id.').pdf');
	}


	public function edit($id)
	{
		$invoice = Invoice::find($id);
		$clients = User::where('parent_id', parentId())->where('type', 'client')->get()->pluck('first_name', 'id');
		return view('invoice.edit', compact('clients', 'invoice'));
	}

	public function update(Request $request, $id)
	{
		if (\Auth::user()->can('show invoice')) {
			$validator = \Validator::make(
				$request->all(),
				[
					'invoice_date' => 'required',
					'due_date' => 'required',
					'total' => 'required',
					'status' => 'required',
				]
			);
			if ($validator->fails()) {
				$messages = $validator->getMessageBag();
				return redirect()->back()->with('error', $messages->first())->withInput();
			}

			$invoice = Invoice::find($id);
			$invoice->invoice_date = $request->invoice_date;
			$invoice->due_date = $request->due_date;
			$invoice->total = $request->total;
			$invoice->final_amount = $request->final_amount;
			$invoice->discount = !empty($request->discount) ? $request->discount : 0;
			$invoice->status = $request->status;
			$invoice->notes = !empty($request->notes) ? $request->notes : null;
			$invoice->save();

			return redirect()->back()->with('success', __('Invoice successfully updated.'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}


	public function destroy($id)
	{

		if (\Auth::user()->can('delete invoice')) {
			$invoice = Invoice::find($id);
			$invoice->delete();
			return redirect()->back()->with('success', __('Invoice successfully deleted.'));
		} else {
			return redirect()->back()->with('error', __('Permission denied.'));
		}
	}

	public function invoiceNumber()
	{
		$lastInvoice = Invoice::where('parent_id', parentId())->latest()->first();
		if ($lastInvoice == null) {
			return 1;
		} else {
			return $lastInvoice->id + 1;
		}
	}

	public function getWorkorder(Request $request)
	{
		$invoice = Invoice::where('client', $request->client)->get()->pluck('wo_id')->toArray();
		$workorders = WorkOrder::where('client', $request->client)->whereNotIn('id', $invoice)->get();
		$woData = [];
		foreach ($workorders as $workorder) {
			$woData[$workorder->id] = workOrderPrefix() . $workorder->wo_id;
		}
		return response()->json($woData);
	}

	public function getWorkorderDetails(Request $request)
	{
		$workorder = WorkOrder::find($request->workorder);
		$getWorkorderTotalAmount = $workorder->getWorkorderTotalAmount();
		return response()->json($getWorkorderTotalAmount);
	}



	public function invoicestorefinal(Request $request)
	{

		// Fetch the work order and get the created_date
		$workOrder = WorkOrder::find($request->workOrderId);

		// Fetch work order
		if (!$workOrder) {
			return response()->json([
				'error' => false,
				'message' => 'there is an error',

			]);
		}


		// Get booking from work order and find its quotation
		$bookingId = json_decode($workOrder->booking, true)[0] ?? null;
		//   dd($workOrder);
		if (!$bookingId) {
			return response()->json([
				'error' => false,
				'message' => 'there is an error',

			]);
		}
		$technician_array = json_decode($workOrder->technician, true) ?? [];
		if (!empty($technician_array)) {
			$technician = User::findOrFail($technician_array[0]);
			if ($technician) {
				$warehouse = $technician->warehouse ?? null;
			}
		}
		$quotation = BookingQuotation::where('booking_id', $bookingId)->where('status', 'Confirmed')->first();
		//dd($quotation->items);
		if ($quotation) {
			// Update the booking status to "Invoice"
			// Create new invoice
			DB::beginTransaction();
			$invoice = new Invoice();
			$invoice->client = $request->customerId;
			$invoice->wo_id = $request->workOrderId;

			// Temporarily set invoice_id to null (it will be updated after saving)
			$invoice->invoice_id = $request->has('invoiceNo') ? $request->invoiceNo : null;

			// Set the invoice date (use provided date or current date)
			$invoice->invoice_date = $request->has('postDate') ? $request->postDate : now();

			// Save the invoice first to generate an ID
			$invoice->save();
			// dd($quotation->items);
			$setup = Inventory_setup::latest()->first() ?? null;
			// Process inventory adjustments
			foreach ($quotation->items as $item) {

				$item->location = $warehouse?->id;
				$item->save();

				$adjustment = ServicePartadjustMent::where('service_part_id', $item->product_id)
					->where('warehouse_id', $warehouse?->id)->first();
				//    

				if ($adjustment && $setup || !$adjustment && $item->item_type == 'Service' && $setup) {
					$available = $adjustment?->onhand - $adjustment?->commited - $adjustment?->unavailable;
					if (($setup->hand_availability == 0 && $item->item_type != 'Service' &&  $available >= $item->qty) || $setup->hand_availability  == 1) {

						$inventory = new InventoryDetail();
						$inventory->service_part_id = $item->product_id;
						$inventory->quantity = '-' . $item->qty;
						//   $inventory->location = $item->location;
						$inventory->unit_price = $item->price;
						$inventory->location = $warehouse?->id;
						$inventory->reference = 'Sales';
						$inventory->document = '#INV' . $invoice->id;
						$inventory->save();

						// Update service part adjustment
						if ($adjustment) {
							$adjustment->unavailable += $item->qty;
							$adjustment->commited -= $item->qty;
							//  $adjustment->available = $adjustment->onhand - $adjustment->unavailable;
							$adjustment->save();
						}
						DB::commit();
					} else {
						DB::rollBack();
						return response()->json([
							'success' => false,
							'message' => 'THere is no stock',
							'work_order_id' => $request->workOrderId
						]);
					}
				} else {

					DB::rollBack();
					return response()->json([
						'success' => false,
						'message' => 'there is no quantity in adjustment reports',
						'work_order_id' => $request->workOrderId
					]);
				}
			}
		}

		$booking = Booking::find($bookingId);
		// if ($booking) {
		//     $booking->status = 'Invoice';
		//     $booking->save();
		// }
		// ✅ Update work order status to "Invoiced"
		$workOrder->status = 'Invoiced';
		$workOrder->save();





		// If invoiceNo is not provided, update invoice_id with the generated invoice ID
		// if (!$request->has('invoiceNo') && $invoice) {
		// 	$invoice?->invoice_id = $invoice?->id;
		// 	$invoice->save(); // Save again to store the generated ID
		// }




		// Get just the date part from created_date (assuming it's a timestamp)
		$createdDate = \Carbon\Carbon::parse($workOrder->created_date)->toDateString();
		$invoice->due_date = $createdDate; // Set the due date to the work order's created date

		$invoice->payment_terms = $request->paymentTerms;
		$invoice->total = $request->totalamount;
		$invoice->final_amount = $request->totalamount;
		$invoice->description = $request->description;
		$invoice->invoice_type = $request->paymentType;
		$invoice->status = "Invoiced";
		$invoice->save();
		// Update invoices in work order
		$existingInvoices = $workOrder->invoice_id ? json_decode($workOrder->invoice_id, true) : [];
		$existingInvoices[] = $invoice->id;
		$workOrder->invoice = json_encode($existingInvoices);
		$workOrder->save();



		$items = Workorder_scrap::where('wo_id', $workOrder->id)->get();
		foreach ($items as $item) {
			$adjustment_scrap = ServicePartadjustMent::where('service_part_id', $item->scrap_id)
				->where('warehouse_id', $warehouse->id)->first() ?? null;

			if ($adjustment_scrap) {
				$adjustment_scrap->onhand += $item->qty;
				$adjustment_scrap->save();
			} else {
				$to_adjustment_scrap = ServicePartadjustMent::create([
					'service_part_id' => $item->scrap_id,
					'warehouse_id' => $warehouse->id,
					'onhand' => $item->qty,
					'commited' => 0,
					'unavailable' => 0,
					'available' => 0
				]);;
			}
			$inventory = new InventoryDetail();
			$inventory->service_part_id = $item->scrap_id;
			$inventory->quantity = '+' . $item->qty;
			$inventory->reference = 'Scraps';
			$inventory->document = '#INV' . $invoice->id;
			$inventory->location = $warehouse->id;
			$inventory->save();
		}
		// Update warranty registrations
		$warrantyRegistrations = $workOrder->warranty ?? null;

		if ($warrantyRegistrations) {
			$warrantyRegistrations = explode(',', $warrantyRegistrations);

			// $warrantyRegistrations = Warranty_registration::where('work_order_id', $request->workOrderId)->get();
			foreach ($warrantyRegistrations as $warranty) {
				$warranty = Warranty_registration::findOrFail($warranty);

				if ($warranty->status == 3) {
					$warranty->claim_count = ($warranty->claim_count ?? 0) + 1;
				} elseif ($warranty->status == 4) {
					$warranty->jump_start = ($warranty->claim_count ?? 0) + 1;
				}
				$warranty->save();
				//  dd($warranty);
			}
		}
		$warranty = AddWarrantyItems::where('workorderid', $workOrder->id)->get() ?? [];
		//dd($warranty);
		$decodedVehicleId = json_decode($workOrder->vehicle, true)[0] ?? null;
		foreach ($warranty as $wrr) {
			$fromDate = Carbon::parse($wrr->from_date);
			$toDate = Carbon::parse($wrr->to_date);
			$warrantyPeriod = $toDate->diffInMonths($fromDate);
			$wreg = Warranty_registration::create([
				'work_order_id' => $wrr->workorderid,
				'product_id' => $wrr->product_id,
				'customer_id' => $workOrder->customer_id,
				'status' => 1,
				'vehicle' => $decodedVehicleId, // Use the decoded integer vehicle ID
				'warranty_start_date' => $wrr->from_date,
				'warranty_end_date' => $wrr->to_date,
				'warranty_period' => $warrantyPeriod
			]);
			$wrr->wreg_id = $wreg->id;
			$wrr->save();
		}






		return response()->json([
			'success' => true,
			'message' => 'Invoice Created Successfully',
			'work_order_id' => $request->workOrderId
		]);
	}
}
