<?php

namespace App\Http\Controllers;

use App\Models\WarrentyItem;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WarHouse;
use App\Models\ServicePart;

class WarrentyItemController extends Controller
{
	use uploadTrait;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$warrentyItems = WarrentyItem::all();
		$serviceParts = ServicePart::all()->pluck('product_name', 'id');
		// $clients=User::where('type','client')->get()->pluck('first_name','id');
		// $vehicles=Vehicle::get()->pluck('rego','id');
		$warehouses = WarHouse::select('id', 'name')->get();
		return view('warrenty.index', compact('warrentyItems', 'serviceParts', 'warehouses'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$serviceParts = ServicePart::all()->pluck('product_name', 'id');
		$clients = collect();
		$warehouses = WarHouse::select('id', 'name')->get();
		$vehicles = collect();
		return view('warrenty.create', compact('serviceParts', 'clients', 'warehouses', 'vehicles'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//  dd($request->all());


		//   if (\Auth::user()->can('create warrenty item')) {
		$validator = \Validator::make($request->all(), [
			'customer_id' => 'required|exists:users,id',
			'product_id' => 'required|exists:service_parts,id',
			'status' => 'required',
			'issued_proof' => 'file|max:2024',
			'issued_description' => 'required|string|max:1000',
			'repair_date' => 'required|date',
			'service_center' => 'required|string|max:100',
			'claim_solution' => 'required|string|max:1000',
			'notes' => 'nullable|string|max:1000',
			'claim_date' => 'required|date',


		]);

		if ($validator->fails()) {
			$messages = $validator->getMessageBag();
			return redirect()->back()->with('error', $messages->first());
		}



		// dd($request->all());

		$warrent_item = new WarrentyItem([
			'customer_id' => $request->input('customer_id'),
			'product_id' => $request->input('product_id'),
			'status' => $request->input('status'),
			'issued_description' => $request->input('issued_description'),
			'repair_date' => $request->input('repair_date'),
			'service_center' => $request->input('service_center'),
			'claim_solution' => $request->input('claim_solution'),
			'notes' => $request->input('notes'),
			'claim_date' => $request->input('claim_date'),
		]);

		//  $warrent_item->issued_proof=$this->verifyAndStoreFile( $request, 'issued_proof' ,  'warranty_extends', 'upload_file') ;
		$warrent_item->save();
		return redirect()->route('warrentyitems.index')->with('success', __('warrenty item created successfully.'));
		// } else {
		//     return redirect()->back()->with('error', __('You do not have permission to create a service or part.'));
		// }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\WarrentyItems  $warrentyItems
	 * @return \Illuminate\Http\Response
	 */
	public function show(WarrentyItem $warrentyItems, $id)
	{
		$bookingitemsid = $id;
		return view('warrenty.productwarrenty', compact('bookingitemsid'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\WarrentyItem  $warrentyItems
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$serviceParts = ServicePart::all()->pluck('product_name', 'id');
		// $clients = User::where('type', 'client')->get()->pluck('first_name', 'id');
		$warrentyItem = WarrentyItem::findOrFail($id);
		$warehouses = WarHouse::select('id', 'name')->get();
        $client=$warrentyItem->customer;
		$clients= collect();
        // $vehicles=collect();
		return view('warrenty.edit', compact('warrentyItem', 'clients','client', 'serviceParts', 'warehouses'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\WarrentyItem  $warrentyItems
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{


		//   if (\Auth::user()->can('edit warrenty item')) {
		$validator = \Validator::make($request->all(), [
			'customer_id' => 'exists:users,id',
			'product_id' => 'exists:service_parts,id',
			'status' => 'in:0,1',
			'issued_proof' => 'file|max:2024',
			'issued_description' => 'nullable|string|max:1000',
			'repair_date' => 'string|max:1000',
			'service_center' => 'string|max:100',
			'claim_solution' => 'nullable|string|max:1000',
			'notes' => 'nullable|string|max:1000',
			'claim_date' => 'date',

		]);

		if ($validator->fails()) {
			$messages = $validator->getMessageBag();
			return redirect()->back()->with('error', $messages->first());
		}

		$warrentyItem = WarrentyItem::find($id);
		if (!$warrentyItem) {
			return redirect()->back()->with('error', __('warrenty item not found.'));
		}



		$warrentyItem->update($request->all());


		return redirect()->route('warrentyitems.index')->with('success', __('warrenty item updated successfully.'));
		// }
		// else {
		//     return redirect()->back()->with('error', __('Permission Denied.'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\WarrentyItem  $warrentyItems
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//if (\Auth::user()->can('delete warrenty item')) {
		$warrentyItem = WarrentyItem::find($id);
		$warrentyItem->delete();
		return redirect()->route('warrentyitems.index')->with('success', __('warrenty item successfully deleted.'));
		// } else {
		//     return redirect()->back()->with('error', __('Permission denied.'));
		// }
	}
}
