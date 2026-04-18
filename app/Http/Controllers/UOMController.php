<?php

namespace App\Http\Controllers;

use App\Models\UOM;
use Illuminate\Http\Request;

class UOMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uom = UOM::where('isDeleted', false)
        ->orWhereNull('isDeleted')->get();
        return view('uom.index',compact('uom'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('uom.create');
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
                'title' => 'required|string|max:255',
                //'value' => 'required|string|max:255',
               
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $group = new UOM();
        $group->title = $request->title;
        $group->value = $request->value;
        // Convert array to comma-separated string
     
        $group->save();

        return redirect()->back()->with('success', __('UOM title successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UOM  $uOM
     * @return \Illuminate\Http\Response
     */
    public function show(UOM $uOM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UOM  $uOM
     * @return \Illuminate\Http\Response
     */
    public function edit(UOM $uOM, $id)
    {
        $edituom = UOM::findOrfail($id);
        return view('uom.edit',compact('edituom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UOM  $uOM
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
    {

        

        $note = UOM::find($id);
        $note->title = $request->title;
        $note->value = $request->value;
        $note->isModified = true;
        $note->save();

        return redirect()->back()->with('success', __('UOM successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UOM  $uOM
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        

        $note = UOM::find($id);
      //  $note->title = $request->title;
        $note->isDeleted = true;
        $note->save();

        return redirect()->back()->with('success', __('UOM successfully updated.'));
    }
}
