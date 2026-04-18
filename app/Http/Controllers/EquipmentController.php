<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\WarHouse;
use App\Models\User;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the equipment.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipments = Equipment::all();
        $warehouse = WarHouse::select('id', 'name')->get();
        return view('equipment.index', compact('equipments', 'warehouse', 'equipments'));
    }

    /**
     * Show the form for creating new equipment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $technicians = User::with('clients')->where('parent_id', parentId())->where('type', 'technician')->get();
        $warehouse = WarHouse::select('id', 'name')->get();
        return view('equipment.create', compact('warehouse', 'technicians'));
    }

    /**
     * Store a newly created equipment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'user_id' => 'required',
                'warehouse_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $user = new Equipment();
        $user->title = $request->title;
        $user->description = $request->description;
        $user->user_id = $request->user_id;
        $user->warehouse_id = $request->warehouse_id;
        $user->status = $request->status;
        $user->save();
        return redirect()->route('equipment.index')->with('success', __('Equipment successfully created.'));
    }

    /**
     * Display the specified equipment.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        $technicians = User::with('clients')->where('parent_id', parentId())->where('type', 'technician')->get();
        $warehouse = WarHouse::select('id', 'name')->get();
        return view('equipment.index', compact('equipments', 'technicians', 'warehouse'));
    }

    /**
     * Show the form for editing the specified equipment.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipment $equipment)
    {
        $technicians = User::with('clients')->where('parent_id', parentId())->where('type', 'technician')->get();
        $warehouse = WarHouse::select('id', 'name')->get();
        return view('equipment.edit', compact('equipment', 'technicians', 'warehouse'));
    }

    /**
     * Update the specified equipment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $equipments = Equipment::find($id);

        $equipments->title = $request->title;
        $equipments->description = $request->description;
        $equipments->user_id = $request->user_id;
        $equipments->warehouse_id = $request->warehouse_id;
        $equipments->status = $request->status;
        $equipments->save();

        return redirect()->back()->with('success', __('Equipment successfully updated.'));
    }

    /**
     * Remove the specified equipment from storage.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->can('delete note')) {
            $equipment = Equipment::find($id);
            $equipment->delete();
            return redirect()->back()->with('success', 'Note successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
