<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fleet;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\WarHouse;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage technician')) {
            $fleets = Fleet::all();
            return view('fleet.index', compact('fleets'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('create technician')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
        $warehouse = WarHouse::select('id','name')->get();
        $technicians = User::with('clients')->where('parent_id', parentId())->where('type', 'technician')->get();
        return view('fleet.create', compact('warehouse','technicians'));
    }
    public function updateStatus(Request $request, $id)
    {
        $user = Fleet::find($id);
        $user->status = $request->has('is_active') ? 1 : 0;   
        $user->save();
    
        if ($user->status == '1') {
            return redirect()->route('fleet.index')->with('success', __('Fleet successfully activated.'));
        } else {
            return redirect()->route('fleet.index')->with('success', __('Fleet successfully deactivated.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('create technician')) {
            $fleet = new Fleet();
            $fleet->name = $request->title;
            $fleet->type = $request->type;
            $fleet->make = $request->make;
            $fleet->model = $request->model;
            $fleet->service = $request->service;
            $fleet->warehouse = implode(',', $request->warehouse_name);
            $fleet->technician = implode(',', $request->technicians);
            $fleet->supervisor = implode(',', $request->supervisors);            
            $fleet->save();

            return redirect()->route('fleet.index')->with('success', __('Technician successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ids)
    {
        if (!Auth::user()->can('show technician')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
        $id = Crypt::decrypt($ids);
        $fleet = Fleet::find($id);
        return view('fleet.show', compact('fleet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('edit technician')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
        $warehouse = WarHouse::select('id','name')->get();
        $technicians = User::with('clients')->where('parent_id', parentId())->where('type', 'technician')->get();
        $fleet = Fleet::find($id);

        return view('fleet.edit', compact('fleet', 'technicians', 'warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('create technician')) {
            $fleet = Fleet::find($id);
            $fleet->name = $request->title;
            $fleet->type = $request->type;
            $fleet->make = $request->make;
            $fleet->model = $request->model;
            $fleet->service = $request->service;
            $fleet->warehouse = implode(',', $request->warehouse_name);
            $fleet->technician = implode(',', $request->technicians);
            $fleet->supervisor = implode(',', $request->supervisors);            
            $fleet->save();

            return redirect()->route('fleet.index')->with('success', __('Technician successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('delete technician')) {
            $user = Fleet::find($id);
            $user->delete();
            return redirect()->route('fleet.index')->with('success', __('Fleet successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
