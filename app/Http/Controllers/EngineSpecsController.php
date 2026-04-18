<?php

namespace App\Http\Controllers;

use App\Models\EngineSpecs;
use App\Http\Controllers\Controller;
use App\Models\vehicle_make;
use App\Models\vehicle_model;
use Illuminate\Http\Request;

class EngineSpecsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enginespecs = EngineSpecs::with('make','model')->has('make')->has('model')
        ->where(function($query) {
            $query->where('isDeleted', false)
                  ->orWhereNull('isDeleted');
        })
        ->get();

        $vmake  = vehicle_make::get();
        $vmodel  = vehicle_model::get();
        return view('enginespecs.index', compact('enginespecs','vmake','vmodel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
        $vmake  = vehicle_make::orderBy('make_name', 'asc')->get();
        $vmodel  = vehicle_model::orderBy('model_name', 'asc')->get();
       
        return view('enginespecs.create', compact('vmake','vmodel'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{

   
   
    EngineSpecs::create([
        'enginespecs' => $request->input('enginespecs'),
        'make_id' => $request->input('make_id'),
        'model_id' => $request->input('model_id'),
    ]);

   
    return redirect()->back()->with('success', 'Engine specs added successfully.');
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EngineSpecs  $engineSpecs
     * @return \Illuminate\Http\Response
     */
    public function show(EngineSpecs $engineSpecs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EngineSpecs  $engineSpecs
     * @return \Illuminate\Http\Response
     */
    public function edit(EngineSpecs $engineSpecs,$id)
    {
        $es = EngineSpecs::findOrFail($id);
        $vmake  = vehicle_make::get();
        $vmodel  = vehicle_model::get();

       
        return view('enginespecs.edit', compact('es','vmake','vmodel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EngineSpecs  $engineSpecs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EngineSpecs $engineSpecs,$id)
    {
       
        $request->validate([
            'enginespecs' => 'required|string|max:255',
            'make_id' => 'required|exists:vehicle_makes,id',
            'model_id' => 'required|exists:vehicle_models,id',
        ]);
    
        $es  = EngineSpecs::findOrFail($id);
        $es->update([
            'enginespecs' => $request->input('enginespecs'),
            'make_id' => $request->input('make_id'),
            'isModified' => true,
            'model_id' => $request->input('model_id'),
        ]);
    
      
        return redirect()->back()->with('success', 'Engine specs updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EngineSpecs  $engineSpecs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicegroup = EngineSpecs::find($id);
    
      
        if ($servicegroup) {
          
         //   $servicegroup->isDeleted = true;
			$servicegroup->isDeleted = 1;
			$servicegroup->save();

    
            return redirect()->back()->with('success', 'Regional Specs marked as deleted.');
        }
    
    
        return redirect()->back()->with('error', 'Service group not found.');
    }
}
