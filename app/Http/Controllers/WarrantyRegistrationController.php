<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

use App\Models\UOM;
use App\Models\Booking;
use App\Models\WorkOrder;
use App\Models\ServicePart;
use App\Models\WarrentyItem;
use App\Models\BookingQuotation;
use App\Models\Warranty_registration;

class WarrantyRegistrationController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$query = Warranty_registration::with([
				'product:id,product_name',
				'customer:id,first_name',
				//'AddWarrantyItems:id,wreg_id,warrantynumber',
				'workorder.inv:id,wo_id,id',
				'vehicleInfo:id,rego'
			])->orderByDesc('id');

			return DataTables::of($query)
				->addIndexColumn()

				->addColumn('warranty_no', fn($row) => $row->warranty_no?? '')
				->addColumn('work_order', fn($row) => '#WO-' . $row->work_order_id)
				->addColumn('rego', fn($row) => $row->vehicleInfo->rego ?? '')
				->addColumn('customer_name', fn($row) => $row->customer->first_name ?? '')
				->addColumn('product_name', fn($row) => $row->product->product_name ?? '')
				->addColumn('invoice_id', fn($row) => $row->workorder->inv->id ?? '')

				->addColumn('warranty_period', fn($row) => $row->warranty_period ? $row->warranty_period . ' months' : '')
				->addColumn('warranty_start_date', fn($row) => $row->warranty_start_date)
				->addColumn('warranty_end_date', fn($row) => $row->warranty_end_date)

				->addColumn('claim_count', fn($row) => $row->claim_count ?? 0)
				->addColumn('jump_start', fn($row) => $row->jump_start ?? 0)

				->addColumn('status_label', function ($row) {
					$now = \Carbon\Carbon::now();
					$endDate = \Carbon\Carbon::parse($row->warranty_end_date);

					if ($endDate->lt($now)) {
						return '<span class="badge badge-primary text-bold h5">Expired</span>';
					}

					return match ((int)$row->status) {
						1 => '<span class="badge badge-success text-bold h5">Active</span>',
						2 => '<span class="badge badge-warning text-bold h5">Suspended</span>',
						3 => '<span class="badge badge-secondary text-bold h5">Already Claimed</span>',
						4 => '<span class="badge badge-info text-bold h5">Already Jump Start</span>',
						default => '<span class="badge badge-light text-bold h5">Unknown</span>',
					};
				})

				->addColumn('action', function ($row) {
					$buttons = '';

					// Edit Permission
					if (auth()->user()->can('edit warranty registration')) {
						$editUrl = route('warrentyRegistration.edit', $row->id);
						$buttons .= <<<HTML
							<a class="text-success customModal" data-size="lg" href="#"
								data-url="$editUrl"
								data-title="Edit Warranty">
								<img src="/assets/img/icons/edit.svg" style="width:25px;height:25px;">
							</a>
						HTML;
					}

					// Delete Permission
					if (auth()->user()->can('delete warranty registration')) {
						$deleteRoute = route('warrentyRegistration.destroy', $row->id);
						$csrf = csrf_field();
						$method = method_field('DELETE');

						$buttons .= <<<HTML
						<form method="POST" action="$deleteRoute" style="display:inline-block;">
							$csrf $method
							<a class="text-danger confirm_dialog" href="#">
								<img src="/assets/img/icons/trash.svg" style="width:25px;height:25px;">
							</a>
						</form>
					HTML;
					}

					return $buttons;
				})
				->rawColumns(['status_label', 'action'])
				->make(true);
		}

		return view('warranty_registration.index');
	}

	public function create()
	{
		$workOrders = WorkOrder::has('inv')->get();
		return view('warranty_registration.create', compact('workOrders'));
	}

	public function store(Request $request)
	{
		//// dd($request->all());
		$validator = \Validator::make($request->all(), [
			'work_order_id' => 'exists:work_orders,id',
			'product_id' => 'exists:service_parts,id',
			'warranty_start_date' => 'date',
			'warranty_period' => 'integer|max:36',
		]);

		if ($validator->fails()) {
			return redirect()->back()->with('error', $validator->getMessageBag()->first());
		}

		Warranty_registration::create([
			'work_order_id' => $request->work_order_id,
			'warranty_no'=>$request->warranty_no,
			'product_id' => $request->product_id,
			'customer_id' => $request->customer_id,
			'status' => $request->status,
			'vehicle' => substr($request->vehicle_id, 4),
			'warranty_start_date' => $request->warranty_start_date,
			'warranty_end_date' => Carbon::createFromDate($request->warranty_start_date)->addMonths($request->warranty_period),
			'warranty_period' => $request->warranty_period,
		]);

		return redirect()->route('warrentyRegistration.index')->with('success', 'Warranty registration created successfully.');
	}

	public function edit($id)
	{
		$warrent_registration = Warranty_registration::findOrFail($id);
		$workOrders = WorkOrder::get();
		return view('warranty_registration.edit', compact('workOrders', 'warrent_registration'));
	}

	public function update(Request $request, $id)
	{
		$warrent_registration = Warranty_registration::findOrFail($id);
		$warrent_registration->update([
			'work_order_id' => $request->work_order_id,
			'warranty_no'=>$request->warranty_no,
			'product_id' => $request->product_id,
			'customer_id' => $request->customer_id,
			'status' => $request->status,
			'vehicle' => substr($request->vehicle_id, 4),
			'warranty_start_date' => $request->warranty_start_date,
			'warranty_end_date' => Carbon::createFromDate($request->warranty_start_date)->addMonths($request->warranty_period),
			'warranty_period' => $request->warranty_period,
		]);

		return redirect()->route('warrentyRegistration.index')->with('success', 'Warranty registration updated successfully.');
	}

	public function destroy($id)
	{
		$warrentyReg = Warranty_registration::findOrFail($id);
		$warrentyReg->delete();
		return redirect()->back()->with('success', 'Warranty registration successfully deleted.');
	}

	public function getWarrantyRegistration($customer_id, $vehicle_id)
	{
		$data = Warranty_registration::with(['product', 'customer', 'vehicleInfo'])
			->where('customer_id', $customer_id)
			->where('vehicle', $vehicle_id)
			->get();
			//dd($data);
			$products = $data->pluck('product')->unique();

		return response()->json([
			"message" => "Warranty registrations retrieved successfully.",
			"warranty_registrations" => $data,
			'products'=>$products
		]);
	}

	public function getWarrantyRegistrationone($customer_id, $vehicle_id)
	{
		$data = Warranty_registration::with(['product', 'customer', 'vehicleInfo'])
			->where('customer_id', $customer_id)
			->where('vehicle', $vehicle_id)
			->first();

		return response()->json([
			"message" => "Warranty registration retrieved successfully.",
			"warranty_registrations" => $data,
		]);
	}

	public function warrantyclaim(Request $request, $id)
	{
		DB::beginTransaction();
		try {
			$warrantyRegistration = Warranty_registration::findOrFail($id);
			$warrantyRegistration->status = 3;
			$warrantyRegistration->save();

			$customerId = $warrantyRegistration->customer_id;
			$productId = $warrantyRegistration->product_id;

			$product = ServicePart::findOrFail($productId);
			$uom_name = UOM::find($product->uom)->value('title');

			WarrentyItem::create([
				'customer_id' => $customerId,
				'product_id' => $productId,
				'warranty_registration_id' => $id,
			]);

			$booking = Booking::where('workorderid', $request->workorderid)->firstOrFail();
			$quotation = BookingQuotation::where('booking_id', $booking->id)->firstOrFail();
			$quotation->status = 'Confirmed';
			$quotation->save();

			DB::table('booking_items')->insert([
				'quotation_id' => $quotation->id,
				'product_id' => $product->id,
				'item_no' => $product->item_no,
				'product_name' => $product->product_name,
				'qty' => 1,
				'uom' => $product->uom,
				'uom_name' => $uom_name,
				'warrenty' => $product->warranty,
				'gstprice' => 0,
				'linetotal' => 0,
				'isWarrenty' => 1,
			]);

			DB::commit();
			return redirect()->back()->with('success', 'Claim attempted successfully.');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('Warranty Claim Error: ' . $e->getMessage(), [
				'id' => $id,
				'customer_id' => $customerId ?? null,
				'product_id' => $productId ?? null,
			]);
			return redirect()->back()->with('error', 'An error occurred while processing the warranty claim.');
		}
	}

	public function jumpstart(Request $request, $id)
	{
		DB::beginTransaction();
		try {
			$warrantyRegistration = Warranty_registration::findOrFail($id);
			$warrantyRegistration->status = 4;
			$warrantyRegistration->save();

			DB::commit();
			return redirect()->back()->with('success', 'Jump Start Added Successfully.');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('Jump Start Error: ' . $e->getMessage(), [
				'id' => $id,
			]);
			return redirect()->back()->with('error', 'An error occurred while processing the jump start.');
		}
	}
}
