<?php

namespace App\Http\Controllers;

use App\Models\EngineSpecs;
use App\Models\ServicePart;
use App\Models\vehicle_make;
use Illuminate\Http\Request;
use App\Models\vehicle_model;
use App\Models\ServicePartwithV;
use Yajra\DataTables\DataTables;


class ServicePartwithVController extends Controller
{
	public function index()
	{
		return view('servicepartwithv.index');
	}

	public function servicePartsList(Request $request)
	{
		$data = ServicePartwithV::with(['servicePart', 'vehicleMake', 'vehicleModel'])
			->select('service_partwith_v_s.*');

		return DataTables::of($data)
			->addIndexColumn()

			->addColumn('sl', function ($row) {
				return ''; // will be replaced in JS
			})
			->addColumn('service_part', fn($row) => $row->servicePart->product_name ?? 'N/A')
			->addColumn('make', fn($row) => $row->vehicleMake->make_name ?? 'N/A')
			->addColumn('model', fn($row) => $row->vehicleModel->model_name ?? 'N/A')
			->addColumn('yom', fn($row) => $row->yom)
			->filterColumn('yom', function ($query, $keyword) {
				$query->where('yom', 'like', "%{$keyword}%");
			})
			->addColumn('engine_spec', fn($row) => $row->engine_spec)
			->addColumn('status', function ($row) {
				return $row->status == 1
					? '<span class="badge badge-success">Active</span>'
					: '<span class="badge badge-warning">Inactive</span>';
			})
			->addColumn('action', function ($row) {
				$editBtn = '';
				$deleteBtn = '';

				if (auth()->user()->can('edit item with vehicle')) {
					$editBtn = '<a href="#" class="text-success customModal" data-url="' . route('servicepartwithvehicle.edit', $row->id) . '" data-title="Edit Service With Vehicle"><img src="' . asset('assets/img/icons/edit.svg') . '" style="width:25px;height:25px;"></a>';
				}

				if (auth()->user()->can('delete item with vehicle')) {
					$deleteBtn = '<a href="#" class="text-danger confirm_dialog"><img src="' . asset('assets/img/icons/trash.svg') . '" style="width:25px;height:25px;"></a>';
				}

				return $editBtn . ' ' . $deleteBtn;
			})

			// 🔍 SEARCHABLE FIELDS (related model)
			->filterColumn('service_part', function ($query, $keyword) {
				$query->whereHas('servicePart', function ($q) use ($keyword) {
					$q->where('product_name', 'like', "%{$keyword}%");
				});
			})
			->filterColumn('make', function ($query, $keyword) {
				$query->whereHas('vehicleMake', function ($q) use ($keyword) {
					$q->where('make_name', 'like', "%{$keyword}%");
				});
			})
			->filterColumn('model', function ($query, $keyword) {
				$query->whereHas('vehicleModel', function ($q) use ($keyword) {
					$q->where('model_name', 'like', "%{$keyword}%");
				});
			})

			->rawColumns(['status', 'action'])

			// 📌 Order by ID column
			->orderColumn('id', function ($query, $order) {
				$query->orderBy('id', $order);
			})

			->setRowId('id')
			->make(true);
	}

	public function create()
	{

		$serviceParts = ServicePart::all();


		$vm = vehicle_make::orderBy('make_name', 'asc')->get()->mapWithKeys(function ($item) {
			return [$item->id => $item->make_name];
		});
		$vm->prepend(__('Select Vehicle Make'), '');

		$vmod = vehicle_model::all()->mapWithKeys(function ($item) {
			return [$item->id => $item->model_name];
		});
		$vmod->prepend(__('Select Vehicle Model'), '');

		$es = EngineSpecs::get();

		return view('servicepartwithv.create', compact('serviceParts', 'vm', 'vmod', 'es'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{


		$validatedData = $request->validate([
			'service_part_id' => 'required|integer|exists:service_parts,id',
			'v_make' => 'required|integer|exists:vehicle_makes,id',
			'v_model' => 'required|integer|exists:vehicle_models,id',
			'yom' => 'required',
			'engine_spec' => 'nullable|string|max:255',
			'status' => 'nullable|integer|in:0,1',
		]);

		try {


			ServicePartwithV::create($validatedData);


			return redirect()->back()->with('success', 'Record created successfully.');
		} catch (\Exception $e) {

			return redirect()->back()->withInput()->with('error', 'An error occurred while creating the record: ' . $e->getMessage());
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\ServicePartwithV  $servicePartwithV
	 * @return \Illuminate\Http\Response
	 */
	public function show(ServicePartwithV $servicePartwithV)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\ServicePartwithV  $servicePartwithV
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$servicePartWithVS = ServicePartwithV::findOrFail($id);
		$serviceParts = ServicePart::all();
		$vehicleMakes = vehicle_make::all();
		$vehicleModels = vehicle_model::all();
		$es = EngineSpecs::get();

		return view('servicepartwithv.edit', compact('servicePartWithVS', 'serviceParts', 'vehicleMakes', 'vehicleModels', 'es'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\ServicePartwithV  $servicePartwithV
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$request->validate([
			'service_part_id' => 'required|exists:service_parts,id',
			'v_make' => 'required|exists:vehicle_makes,id',
			'v_model' => 'required|exists:vehicle_models,id',
			'yom' => 'required',
			'engine_spec' => 'required|string|max:255',
			'status' => 'nullable|boolean',
		]);

		$servicePartWithVS = ServicePartWithV::findOrFail($id);


		$data = $request->all();
		$data['isModified'] = true;

		$servicePartWithVS->update($data);

		return redirect()->back()->with('success', 'Record updated successfully.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\ServicePartwithV  $servicePartwithV
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$servicePartWithVS = ServicePartWithV::findOrFail($id);
		$servicePartWithVS->delete();

		return redirect()->back()->with('success', 'Record deleted successfully.');
	}
}
