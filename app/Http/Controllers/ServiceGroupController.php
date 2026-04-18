<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceGroups;
use App\Models\ServiceMaster;
use App\Models\vehicle_make;
use App\Models\vehicle_model;

class ServiceGroupController extends Controller
{
    public function index()
    {
    $servicegroups = ServiceGroups::with('serviceMasters')
        ->orderBy('id', 'desc') 
        ->get();

    $vm = vehicle_make::all()->pluck('make_name', 'id');
    $vm->prepend(__('Select Vehicle Make'), '');

    $vmod = vehicle_model::all()->pluck('model_name', 'id');
    $vmod->prepend(__('Select Vehicle Model'), '');

    $servicemaster = ServiceMaster::all();

    return view('service_group.index', compact('servicegroups', 'vm', 'vmod', 'servicemaster'));
    }


    public function create()
    {
        $vm = vehicle_make::orderBy('make_name', 'asc')->pluck('make_name', 'id')->toArray();
        $vm = ['' => __('Select Vehicle Make')] + $vm;
    
        $vmod = vehicle_model::pluck('model_name', 'id')->toArray();
        $vmod = ['' => __('Select Vehicle Model')] + $vmod;
    
        $servicemaster = ServiceMaster::all();
    
        return view('service_group.create', compact('vm', 'vmod', 'servicemaster'));
    }
    
    public function store(Request $request)
    {
     
        
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                
                'service_master' => 'required|array',
                'service_master.*' => 'exists:service_masters,id'
            ]
        );
    
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
    
        $servicegroups = new ServiceGroups();
        $servicegroups->name = $request->name;
        $servicegroups->vmod_id = $request->vmod_id;
        $servicegroups->vm_id = $request->vm_id;
        $servicegroups->service_master_id = implode(',', $request->service_master); 
        $servicegroups->save();
    
        return redirect()->back()->with('success', __('Service Group successfully created.'));
    }
    

    public function edit($id)
    {
        $servicegroups = ServiceGroups::findOrFail($id);

        $vm = vehicle_make::all()->pluck('make_name', 'id');
        $vm->prepend(__('Select Vehicle Make'), '');

        $vmod = vehicle_model::all()->pluck('model_id', 'id');
        $vmod->prepend(__('Select Vehicle Model'), '');

        $servicemaster = ServiceMaster::all();
        return view('service_group.edit', compact('servicegroups','vm','vmod','servicemaster'));
    }


    public function update(Request $request, $id)
{
    $validator = \Validator::make(
        $request->all(),
        [
            'name' => 'string|max:255',
            'vm_id' => 'exists:vehicle_makes,id',
            'vmod_id' => 'exists:vehicle_models,id',
            'service_master' => 'array',
            'service_master.*' => 'exists:service_masters,id'
        ]
    );

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    $servicegroups = ServiceGroups::findOrFail($id);
    $servicegroups->name = $request->name;
    $servicegroups->vm_id = $request->vm_id;
    $servicegroups->vmod_id = $request->vmod_id;
    $servicegroups->service_master_id = implode(',', $request->service_master);
    $servicegroups->save();

    return redirect()->back()->with('success', __('service group successfully updated.'));
}


    public function destroy($id)
    {
        $servicegroups = ServiceGroups::find($id);
        $servicegroups->delete();
        return redirect()->back()->with('success', 'service group successfully deleted.');
    }
}