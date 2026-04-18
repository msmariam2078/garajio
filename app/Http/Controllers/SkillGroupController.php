<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use App\Models\SkillList;
use App\Models\SkillGroup;
use App\Models\vehicle_make;
use App\Models\vehicle_model;


class SkillGroupController extends Controller
{
    public function index()
{
    $groups = SkillGroup::with('skill', 'vehicleMake', 'vehicleModel')->get(); 
  
    return view('skill_group.index', compact('groups'));
}
    public function create()
    {
        $skills = SkillList::all()->pluck("skill_name","id");
        $vm = vehicle_make::orderBy('make_name', 'asc')->get()->pluck('make_name', 'id');
        $vm->prepend(__('Select Vehicle Make'), '');

        $vmod = vehicle_model::orderBy('model_name', 'asc')->get()->pluck('model_name', 'id');
        $vmod->prepend(__('Select Vehicle Model'), '');
        return view('skill_group.create', compact('skills','vm','vmod'));
    }

    public function store(Request $request)
{
    $validator = \Validator::make(
        $request->all(),
        [
            'group_name' => 'required|string|max:255',
            'skill_id' => 'required|array',
            'skill_id.*' => 'exists:skill_lists,id',
            'vehicle_make' => 'nullable|exists:vehicle_makes,id', // Validate vehicle make
            'vehicle_model' => 'nullable|exists:vehicle_models,id', // Validate vehicle model
        ]
    );

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    $group = new SkillGroup();
    $group->group_name = $request->group_name;

    // Convert array to comma-separated string
    $group->skill_id = implode(',', $request->skill_id);

    // Assign the vehicle make and model
    $group->vehicle_make_id = $request->vehicle_make;
    $group->vehicle_model_id = $request->vehicle_model;

    $group->save();

    return redirect()->back()->with('success', __('Skill group successfully created.'));
}

    public function edit($id)
    {
        $group = SkillGroup::findOrFail($id);
        $skills = SkillList::all();
        $selectedSkillIds = explode(',', $group->skill_id);

        $vm = vehicle_make::all()->pluck('make_name', 'id');
        $vm->prepend(__('Select Vehicle Make'), '');

        $vmod = vehicle_model::all()->pluck('model_name', 'id');
        $vmod->prepend(__('Select Vehicle Model'), '');
        return view('skill_group.edit', compact('group', 'skills', 'selectedSkillIds','vm','vmod'));
    }


    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'group_name' => 'required|string|max:255',
                'skill_id' => 'required|array',
                'skill_id.*' => 'exists:skill_lists,id',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $group = SkillGroup::findOrFail($id);
        $group->group_name = $request->group_name;
        // Convert array to comma-separated string
        $group->skill_id = implode(',', $request->skill_id);
        $group->save();

        return redirect()->back()->with('success', __('Skill group successfully updated.'));
    }


    public function destroy($id)
    {
        $group = SkillGroup::find($id);
        $group->delete();
        return redirect()->back()->with('success', 'Skill Group successfully deleted.');
    }
}