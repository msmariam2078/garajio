<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\vehicle_make;
use App\Models\vehicle_model;

class VehicleMakeController extends Controller
{
    public function index()
    {
      
        $vmake = vehicle_make::where('isDeleted', false)
        ->orWhereNull('isDeleted')->get();

        return view('vehiclemake.index', compact('vmake'));
    }

    public function create()
    {
     
        return view('vehiclemake.create');
    }

    public function store(Request $request)
    {
    $validator = \Validator::make(
        $request->all(),
        [
            'make_name' => 'required|string|max:255|unique:vehicle_makes,make_name',
        ]
    );

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    $vmake = new vehicle_make();
    $vmake->make_name = strtoupper($request->make_name);
    $vmake->save();

    return redirect()->back()->with('success', __('Vehicle Make successfully created.'));
    }

    

    public function edit($id)
{
    $vm = vehicle_make::findOrFail($id);

    return view('vehiclemake.edit', compact('vm'));
}



public function update(Request $request, $id)
{
    $validator = \Validator::make(
        $request->all(),
        [
            'make_name' => 'required|string|max:255|unique:vehicle_makes,make_name',
        ]
    );

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    $vehicleMake = vehicle_make::findOrFail($id);
    $vehicleMake->make_name = strtoupper($request->make_name);
    $vehicleMake->isModified = true;
    $vehicleMake->save();

    return redirect()->back()->with('success', __('Vehicle Make successfully updated.'));
    }



    public function destroy($id)
    {
        $servicegroups = vehicle_make::find($id);
		$servicegroups->isDeleted = 1;
		$servicegroups->save();

        return redirect()->back()->with('success', 'Vehicle Make  successfully deleted.');
    }

    public function storeajax(Request $request)
    {
        $vehicleMake = null;
        $vehicleModel = null;
    
       
        if ($request->filled('vm1')) {
            $vehicleMake = vehicle_make::create([
                'make_name' => $request->input('vm1'),
            ]);
        }
    
     
        if ($request->filled('vmodid') && $request->filled('vmodname')) {
            $vehicleModel = vehicle_model::create([
                'model_id' => $request->input('vmodid'),
                'model_name' => $request->input('vmodname'),
            ]);
        }
 
        return response()->json([
            'success' => true,
            'data' => [
                'make_id' => $vehicleMake ? $vehicleMake->id : null,
                'make_name' => $vehicleMake ? $vehicleMake->make_name : null,
                'model_id' => $vehicleModel ? $vehicleModel->id : null,
                'model_name' => $vehicleModel ? $vehicleModel->model_name : null,
            ]
        ]);
    }
    

}