<?php

namespace App\Http\Controllers;

use App\Models\TechnicianLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechnicianLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    
    $technicianlog = TechnicianLog::with('user')->get();

    
    return view('technicianlog.index', compact('technicianlog'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TechnicianLog  $technicianLog
     * @return \Illuminate\Http\Response
     */
    public function show(TechnicianLog $technicianLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TechnicianLog  $technicianLog
     * @return \Illuminate\Http\Response
     */
    public function edit(TechnicianLog $technicianLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TechnicianLog  $technicianLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TechnicianLog $technicianLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TechnicianLog  $technicianLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(TechnicianLog $technicianLog)
    {
        //
    }
}