<?php

namespace App\Http\Controllers;

use App\Models\vehicle_make;
use Illuminate\Http\Request;
use App\Models\vehicle_model;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class VehicleModelController extends Controller
{
	public function index()
	{
		$vmodel = vehicle_model::with('make')->where('isDeleted', 0)
			->orWhereNull('isDeleted')->get();

		return view('vehiclemodel.index', compact('vmodel'));
	}


	public function create()
	{
		$vmake = vehicle_make::orderBy('make_name', 'asc')->get();
		return view('vehiclemodel.create', compact('vmake'));
	}

	public function store(Request $request)
	{
		$validator = \Validator::make(
			$request->all(),
			[
				'model_name' => [
					'required',
					'string',
					'max:255',
					Rule::unique('vehicle_models', 'model_name')
						->where(fn($query) => $query->where('make_id', $request->make_id))
				],
				'make_id' => 'required|exists:vehicle_makes,id',
			]
		);

		if ($validator->fails()) {
			$messages = $validator->getMessageBag();
			return redirect()->back()->with('error', $messages->first());
		}

		$vmake = new vehicle_model();
		$vmake->make_id = $request->make_id;
		$vmake->model_name = strtoupper($request->model_name);
		$vmake->save();

		return redirect()->back()->with('success', __('Vehicle Model successfully created.'));
	}

	public function edit($id)
	{
		$vmodel = vehicle_model::findOrFail($id);
		$vmakes = vehicle_make::all();

		return view('vehiclemodel.edit', compact('vmodel', 'vmakes'));
	}
	public function update(Request $request, $id)
	{
		$validator = \Validator::make(
			$request->all(),
			[
				'model_name' => 'required|string|max:255|unique:vehicle_models,model_name',
				'make_id' => 'required|exists:vehicle_makes,id',
			]
		);

		if ($validator->fails()) {
			$messages = $validator->getMessageBag();
			return redirect()->back()->with('error', $messages->first());
		}

		$vmodel = vehicle_model::findOrFail($id);
		$vmodel->model_name = strtoupper($request->model_name);
		$vmodel->make_id = $request->make_id;
		$vmodel->isModified = true;
		$vmodel->save();

		return redirect()->route('vehiclemodel.index')->with('success', __('Vehicle Model successfully updated.'));
	}



	public function destroy($id)
	{
		$servicegroup = vehicle_model::find($id);


		if ($servicegroup) {

			$servicegroup->isDeleted = true;
			$servicegroup->save();

			return redirect()->back()->with('success', 'Vehicle Model marked as deleted.');
		}


		return redirect()->back()->with('error', 'Service group not found.');
	}
}
