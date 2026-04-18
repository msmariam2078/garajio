<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        $groupname = CustomerGroup::get();

        return view('customergroup.index', compact('groupname'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'group_name' => 'required|string|max:255',
                
            ]
        );
    
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
    
        $groupname = new CustomerGroup();
        $groupname->group_name = $request->group_name;
        $groupname->save();
        return redirect()->back()->with('success', __('Group Name successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerGroup $customerGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerGroup $customerGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $group = CustomerGroup::find($id);
        $group->update($request->all());
        return redirect()->back()->with('success', 'customer group  successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $group = CustomerGroup::find($id);
        $group->delete();
        return redirect()->back()->with('success', 'customer group  successfully deleted.');
    }
}
