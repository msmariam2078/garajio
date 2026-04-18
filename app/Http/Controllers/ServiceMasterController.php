<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceMaster;
use App\Models\unit;
use App\Models\SkillGroup;

class ServiceMasterController extends Controller
{
    public function index()
    {

        $services  = ServiceMaster::all();
        
        $units = unit::get();
        $skilgroups = SkillGroup::get();
        return view('service.index', compact('services','units', 'skilgroups'));
    }
    public function create()
    {

        $units = unit::get();
        $skilgroups = SkillGroup::get();
        return view('service.create', compact('units', 'skilgroups'));
    }

    public function store(Request $request)
{ 
    //dd($request->all());
    $validator = \Validator::make(
        $request->all(),
        [
            'title' => 'required|string|max:255',
           
            'skill_id' => 'required|array',
            'skill_id.*' => 'integer',
        ]
    );

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    $service = new ServiceMaster();
    $service->title = $request->title;
    $service->price = $request->price;
    $service->unitId = $request->unit_id;
    $service->skillId = implode(',', $request->skill_id);
    $service->description = $request->description;
    $service->save();

    return redirect()->back()->with('success', __('Service successfully created.'));
}


    public function edit($id)
    {
        $service = ServiceMaster::findOrFail($id);
        $units = Unit::get();
        $skilgroups = SkillGroup::get();
        return view('service.edit', compact('service', 'units', 'skilgroups'));
    }


    public function update(Request $request, $id)

    {
     //dd($request->all());
    $validator = \Validator::make(
        $request->all(),
        [
            'title' => 'required|string|max:255',
            
            'skill_id' => 'required|array',
            'description' => 'required|string',
        ]
    );

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    $service = ServiceMaster::findOrFail($id);
    $service->title = $request->title;
    $service->price = $request->price;
    $service->unitId = $request->unit_id;
    $service->skillId = implode(',', $request->skill_id);
    $service->description = $request->description;
    $service->save();

    return redirect()->back()->with('success', __('Service successfully updated.'));
}



    public function destroy($id)
    {
        $service = ServiceMaster::find($id);
        $service->delete();
        return redirect()->back()->with('success', 'Service successfully deleted.');
    }


    public function show($id)
    {
        $service = ServiceMaster::findOrFail($id);
        return view('service.show', compact('service'));
    }
}