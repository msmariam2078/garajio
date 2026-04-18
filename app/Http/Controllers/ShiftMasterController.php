<?php

namespace App\Http\Controllers;

use App\Models\ShiftMaster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = ShiftMaster::all();
        return view('shiftmasters.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shiftmasters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'days' => 'required|array',
        'days.*' => 'string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        'start_time' => 'required',
        'end_time' => 'required',
    ]);

    // Serialize the days array to a string (JSON format)
    $days = json_encode($request->days);

    // Create the shift master record with all days stored in the same variable
    ShiftMaster::create([
        'title' => $request->title,
        'description' => $request->description,
        'days' => $days, // Storing the entire days array as a JSON string
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
    ]);

    return redirect()->route('shiftmasters.index')->with('success', 'Shift Master created successfully.');
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShiftMaster  $shiftMaster
     * @return \Illuminate\Http\Response
     */
    public function show(ShiftMaster $shiftMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShiftMaster  $shiftMaster
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    $shiftMaster = ShiftMaster::findOrFail($id);
    return view('shiftmasters.edit', compact('shiftMaster'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShiftMaster  $shiftMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'days' => 'required|array',
            'days.*' => 'string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
    
        
        $shiftMaster = ShiftMaster::findOrFail($id);
    
       
        $days = json_encode($request->days);
    
    
        $shiftMaster->update([
            'title' => $request->title,
            'description' => $request->description,
            'days' => $days,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
    
        return redirect()->route('shiftmasters.index')->with('success', 'Shift Master updated successfully.');
    }
    


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShiftMaster  $shiftMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $shiftMaster = ShiftMaster::findOrFail($id);

    // Delete all records with the same title to ensure all days are removed
    ShiftMaster::where('title', $shiftMaster->title)->delete();

    return redirect()->route('shiftmasters.index')->with('success', 'Shift Master deleted successfully.');
    }

}