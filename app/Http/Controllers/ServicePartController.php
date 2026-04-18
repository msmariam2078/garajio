<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Origin;
use App\Models\service;
use App\Models\ServicePart;
use App\Models\ServiceTask;
use App\Models\WarHouse;
use App\Models\unit;
use App\Models\UOM;
use App\Models\Inventory_setup;
use Illuminate\Http\Request;
use App\Models\SkillGroup;
use App\Models\Vehicle;
use App\Models\ServicePartadjustMent;
use DB;
use App\Models\vehicle_make;
use App\Models\vehicle_model;

class ServicePartController extends Controller
{

	public function index()
	{
		$serviceParts = ServicePart::select(
			'service_parts.*',
			//'categories.description as category_description'
		)
			//->join('categories', 'service_parts.category', '=', 'categories.id')
			->orderBy('service_parts.id', 'asc')
			->get();

		return view('service_part.index', compact('serviceParts'));
	}



	public function create()
	{
		$origin = Origin::all()->pluck('description', 'id');
		$origin->prepend(__('Select Origin'), '');


		$uom = UOM::where('isDeleted', false)
			->orWhereNull('isDeleted')
			->pluck('title', 'id');
		$uom->prepend(__('Select Unit'), '');


		$brand = Brand::all();


		$origin = Origin::all();

		$category = Category::where('parent', '==', 0)->pluck('description', 'id');
		$category->prepend(__('Select Category'), '');

		$subcategory = Category::where('parent', '!=', 0)->pluck('description', 'id');
		$subcategory->prepend(__('Select Sub-Category'), '');

		$types = service::$types;
		$availability = service::$availability;

		$serviceunits = unit::get();
		$skillgroups = SkillGroup::get();


		$warehouse = WarHouse::pluck('name', 'id');
		$warehouse->prepend(__('Select Warehouse'), '');


		$vm = vehicle_make::all()->pluck('make_name', 'id');
		$vm->prepend(__('Select Vehicle Make'), '');

		$vmod = vehicle_model::all()->pluck('model_name', 'id');
		$vmod->prepend(__('Select Vehicle Model'), '');

		$setup=Inventory_setup::latest()->first() ?? null;

		return view('service_part.create', compact('origin', 'uom', 'brand', 'category','setup', 'subcategory', 'types', 'availability', 'serviceunits', 'skillgroups', 'warehouse', 'vm', 'vmod'));
	}

	public function store(Request $request)
	{


		$unitId = $request->unit;
		$unitTitle = UOM::where('id', $unitId)->value('title');


		if (\Auth::user()->can('create service & part')) {
			$validator = \Validator::make($request->all(), [
				'item_type' => 'required',
				//'item_number' => 'required|unique:servi',
				'description' => 'nullable|string|max:255',
				'item_group' => 'nullable|integer',
				'category' => 'nullable|integer',
				'type' => 'nullable|string|max:255',
				'qty_on_hand' => 'nullable|numeric',
				'unit' => 'nullable|integer',
				'sales_price' => 'nullable|numeric',
				'price' => 'nullable|numeric',
				'tax' => 'nullable|numeric',
				'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
				'warranty' => 'nullable|string|max:255',
				'product' => 'nullable|string|max:255',
				'des' => 'nullable|string|max:255',
				'quantity' => 'nullable|numeric',
				'pri' => 'nullable|numeric',

			]);

			if ($validator->fails()) {
				$messages = $validator->getMessageBag();
				return redirect()->back()->with('error', $messages->first());
			}

			$imagePath = null;
			if ($request->hasFile('image')) {
				$file = $request->file('image');
				$extension = $file->getClientOriginalExtension();
				$filename = time() . '.' . $extension;
				$file->move('upload/img/', $filename);
				$image = 'upload/img/' . $filename;
			}



			$item = new ServicePart([
				'item_type' => $request->input('item_type'),
				'item_no' => $request->input('item_number'),
				'product_name' => $request->input('description'),
				'item_group' => $request->input('item_group'),
				'category' => $request->input('category'),
				'type' => $request->input('type'),
				'qty_on_hand' => $request->input('qty_on_hand'),
				'uom' => $unitId,
				'sales_price' => $request->input('sales_price'),
				'price' => $request->input('price'),
				'tax' => $request->input('tax'),
				'image' => $imagePath,
				'warranty' => $request->input('warranty'),
				'product' => $request->input('product'),
				'des' => $request->input('des'),
				'quantity' => $request->input('quantity'),
				'pri' => $request->input('pri'),

				'origin' => $request->input('origin'),
				'brand' => $request->input('brand'),
				'v_make' => $request->input('vm'),
				'v_model' => $request->input('v_make'),
				'warehouse_id' => $request->input('warehouse'),
				'parent_id' => parentId(),
				'reference_type' => $request->input('reference_type'),
				'reference_number' => $request->input('reference_number'),
			]);




			$item->save();

			$adjustment = new ServicePartadjustMent([
				'warehouse_id' => $request->input('warehouse'),
				'service_part_id' => $item->id,
				'unavailable' => 0,
				'commited' => 0,
				'available' => 0,
				'onhand' => 0,
			]);

			$adjustment->save();

			return redirect()->route('services-parts.index')->with('success', __('Service or Part created successfully.'));
		} else {
			return redirect()->back()->with('error', __('You do not have permission to create a service or part.'));
		}
	}

	public function show($id)
	{
		$servicePart = ServicePart::find($id);
		return view('service_part.show', compact('servicePart'));
	}

	public function edit($id)
	{

		$servicePart = ServicePart::findOrfail($id);
		$origin = origin::all();


		$uom = UOM::where('isDeleted', false)
			->orWhereNull('isDeleted')
			->get();
		$brand = brand::all();
		$warehouse = WarHouse::pluck('name', 'id');

		$category = Category::where('parent', '==', 0)->pluck('description', 'id');
		$category->prepend(__('Select Category'), '');

		$subcategory = Category::where('parent', '!=', 0)->pluck('description', 'id');
		$subcategory->prepend(__('Select Sub-Category'), '');

		$types = service::$types;
		$availability = service::$availability;



		return view('service_part.edit', compact('servicePart', 'origin', 'uom', 'brand', 'category', 'warehouse', 'subcategory', 'types', 'availability'));
	}

	public function update(Request $request, $id)
	{



		if (\Auth::user()->can('edit service & part')) {
			$validator = \Validator::make($request->all(), [

				'description' => 'nullable|string|max:255',
				'item_group' => 'nullable|integer',
				'category' => 'nullable|integer',
				'type' => 'nullable|string|max:255',
			//	'item_number' => 'required|unique:service_parts,item_no',
				'qty_on_hand' => 'nullable|numeric',
				'unit' => 'nullable|integer',
				'retailprice' => 'nullable|numeric',
				'price' => 'nullable|numeric',
				'tax' => 'nullable|numeric',
				'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
				'warranty' => 'nullable|string|max:255',
				'product' => 'nullable|string|max:255',
				'des' => 'nullable|string|max:255',
				'quantity' => 'nullable|numeric',
				'pri' => 'nullable|numeric',

			]);

			if ($validator->fails()) {
				$messages = $validator->getMessageBag();
				return redirect()->back()->with('error', $messages->first());
			}

			$servicePart = ServicePart::find($id);
			if (!$servicePart) {
				return redirect()->back()->with('error', __('Service or Part not found.'));
			}

			$imagePath = $servicePart->image; // Keep existing image if no new image is uploaded
			if ($request->hasFile('image')) {
				$file = $request->file('image');
				$extension = $file->getClientOriginalExtension();
				$filename = time() . '.' . $extension;
				$file->move('upload/img/', $filename);
				$imagePath = 'upload/img/' . $filename;
			}
			// dd($request->all());
			$servicePart->update([
				'item_type' => $request->input('item_type'),
				'product_name' => $request->input('description'),
				'item_group' => $request->input('item_group'),
				'category' => $request->input('category'),
				'type' => $request->input('type'),
				'qty_on_hand' => $request->input('qty_on_hand'),
				'uom' => $request->input('unit'),
				'sales_price' => $request->input('sales_price'),
				'price' => $request->input('price'),
				'tax' => $request->input('tax'),
				'image' => $imagePath,
				'warranty' => $request->input('warranty'),
				'product' => $request->input('product'),
				'des' => $request->input('des'),
				'quantity' => $request->input('quantity'),
				'pri' => $request->input('pri'),
				'origin' => $request->input('origin'),
				'brand' => $request->input('brand'),
				'warehouse_id' => $request->input('warehouse'),
				'item_no' => $request->input('item_number'),
				'reference_type' => $request->input('reference_type'),
				'reference_number' => $request->input('reference_number'),
			]);

			return redirect()->route('services-parts.index')->with('success', __('Service or Part updated successfully.'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}


	public function destroy($id)
	{
		if (\Auth::user()->can('delete service & part')) {
			$servicePart = ServicePart::find($id);
			$servicePart->delete();
			return redirect()->route('services-parts.index')->with('success', __('Service & Part successfully deleted.'));
		} else {
			return redirect()->back()->with('error', __('Permission denied.'));
		}
	}

	public function taskDestroy(Request $request)
	{

		if (!empty($request->id)) {
			$task = ServiceTask::find($request->id);
			$task->delete();
		}
		return 1;
	}

	public function masters(Request $request)
	{
		$func = $request->function_name;
		if ($func == 'bra') {
			$brand = new brand();
			$brand->description = $request->bra;
			$brand->save();
		} elseif ($func == 'ig') {
			$brand = new origin();
			$brand->description = $request->ig;
			$brand->save();
		} elseif ($func == 'cat') {
			$brand = new Category();
			$brand->parent = 0;
			$brand->description = $request->cat;
			$brand->save();
		} elseif ($func == 'scat') {
			$brand = new Category();
			$brand->parent = 2;
			$brand->description = $request->scat;
			$brand->save();
		} elseif ($func == 'unit') {
			$brand = new unit();
			$brand->description = $request->createunit;
			$brand->value = $request->unitv;
			$brand->save();
		}
		if ($brand->save() === true) {
			echo "Success";
		} else {
			echo "Failed";
		}
	}

	public function search(Request $request)
	{
		$query = trim($request->get('search', ''));
		$itemType = $request->itemType ?? 'Inventory';
		$vehicleId = $request->get('vehicle_id');
		$allProducts = $request->get('allproducts');
		$products = ServicePart::with('warehouse');		

		if ($query) {
			$products->where(function ($q) use ($query) {
				$q->where('product_name', 'like', '%' . $query . '%')
				->orWhere('item_no', 'like', '%' . $query . '%');
			});
		}


		if ($itemType) {
		    $products->where('item_type', $itemType);
		}


		if ($allProducts == 1 && $vehicleId) {
			$vehicle = Vehicle::find($vehicleId);
			if ($vehicle) {
				$vMake = $vehicle->v_make;
				$vModel = $vehicle->vm;

				$products->whereHas('servicePartAdjustments', function ($query) use ($vMake, $vModel) {
					$query->join('service_partwith_v_s', 'service_partwith_v_s.service_part_id', '=', 'service_partadjust_ments.service_part_id')
						->where('service_partwith_v_s.v_make', $vMake)
						->where('service_partwith_v_s.v_model', $vModel);
				});
			}
		}

		$products = $products->limit(10)
			->get(['id', 'product_name', 'item_no','type', 'sales_price', 'item_type', 'discount_percentage', 'warranty', 'warehouse_id', 'tax', 'uom'])
			->map(function ($product) {
				return [
					'id' => $product->id,
					'product_name' => $product->product_name,
					'item_no' => $product->item_no,
					'type' => $product->type,
					'price' => $product->sales_price,
					'item_type' => $product->item_type,
					'discount_percentage' => $product->discount_percentage,
					'warranty' => $product->warranty,
					'warehouse_name' => $product->warehouse_id ?? null,
					'tax' => $product->tax,
					'uom' => $product->uom,
					'uom_name' => $product->u_o_m?->title,
					'qty' => 1
				];
			});

		return response()->json($products, 200);
	}

	public function getwarehouseproduct(Request $request)

	{
       $products=DB::table('service_parts')->join('u_o_m_s','service_parts.uom','=','u_o_m_s.id')
	    ->join('service_partadjust_ments', 'service_parts.id', '=', 'service_partadjust_ments.service_part_id')
		->where('service_partadjust_ments.warehouse_id',$request->warehouse_id)
		->where('service_partadjust_ments.onhand','>=','0')
	
		->select( 'service_parts.id','service_parts.product_name', 'service_parts.item_no','service_parts.uom','service_partadjust_ments.onhand as stock','u_o_m_s.title')

		->get();
		return response()->json($products, 200);
	}

	public function search2(Request $request)
	{
		$query = trim($request->get('search', ''));
		$itemType = 'scrap';
		//$vehicleId = $request->get('vehicle_id');
	//	$allProducts = $request->get('allproducts');
		$products = ServicePart::with('warehouse');


		if ($query) {
			$products->where('product_name', 'like', '%' . $query . '%');
		}


		if ($itemType) {
		    $products->where('item_type', $itemType);
		}


		

		$products = $products->limit(10)
			->get(['id', 'product_name','item_no', 'item_type','quantity'])
			->map(function ($product) {
				return [
					'id' => $product->id,
					'product_name' => $product->product_name,
					'item_no' => $product->item_no,
					'item_type' => $product->item_type,
	
					'qty' => $product->quantity
				];
			});

		return response()->json($products, 200);
	}



	public function getwarehouse(Request $request)
	{
		// dd($request->service_part_id);
		$item_id = $request->service_part_id;
		$item = ServicePart::find($item_id);
		$warehouse = $item->warehouse;

		return response()->json(['warehouse' => $warehouse]);
	}
}
