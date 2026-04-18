<?php

namespace App\Http\Controllers;

use App\Models\RegionalSpecs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegionalSpecsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        $regionalspecs = RegionalSpecs::where(function($query) {
            $query->where('isDeleted', false)
                  ->orWhereNull('isDeleted');
        })->get();

        return view('regionalspecs.index', compact('regionalspecs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('regionalspecs.create');
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
            'group_name' => 'required|string|max:255|unique:regional_specs,title',
           
        ]
    );

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    $regionalSpecs = new RegionalSpecs();
    $regionalSpecs->title = $request->group_name;
    $regionalSpecs->save();

    return redirect()->back()->with('success', __('Regional Specs successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RegionalSpecs  $regionalSpecs
     * @return \Illuminate\Http\Response
     */
    public function show(RegionalSpecs $regionalSpecs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RegionalSpecs  $regionalSpecs
     * @return \Illuminate\Http\Response
     */
    public function edit(RegionalSpecs $regionalSpecs,$id)
    {
        $rs = RegionalSpecs::findOrFail($id);
       

       
        return view('regionalspecs.edit', compact('rs'));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RegionalSpecs  $regionalSpecs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegionalSpecs $regionalSpecs,$id)
    {
       
        $request->validate([
            'title' => 'required|string|max:255',
           
        ]);
    
        $rs  = RegionalSpecs::findOrFail($id);
        $rs->update([
            'title' => $request->input('title'),
           
            'isModified' => true,
           
        ]);
    
      
        return redirect()->back()->with('success', 'Regional specs updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RegionalSpecs  $regionalSpecs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    $servicegroup = RegionalSpecs::find($id);

  
    if ($servicegroup) {
      
        $servicegroup->isDeleted = true;
        $servicegroup->save();

        return redirect()->back()->with('success', 'Regional Specs marked as deleted.');
    }


    return redirect()->back()->with('error', 'Service group not found.');
}

}