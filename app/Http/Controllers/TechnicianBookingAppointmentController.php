<?php

namespace App\Http\Controllers;

use App\Models\TechnicianBookingAppointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechnicianBookingAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\TechnicianBookingAppointment  $technicianBookingAppointment
     * @return \Illuminate\Http\Response
     */
    public function show(TechnicianBookingAppointment $technicianBookingAppointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TechnicianBookingAppointment  $technicianBookingAppointment
     * @return \Illuminate\Http\Response
     */
    public function edit(TechnicianBookingAppointment $technicianBookingAppointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TechnicianBookingAppointment  $technicianBookingAppointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TechnicianBookingAppointment $technicianBookingAppointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TechnicianBookingAppointment  $technicianBookingAppointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(TechnicianBookingAppointment $technicianBookingAppointment)
    {
        //
    }
    public function getEvents()
    {
        $techId=\Auth::id();
        $techWorkHours=TechnicianBookingAppointment::whereHas('workorder')->with('workorder')->where('technician_id',$techId)->whereNotNull(['from_date','from_time','to_time'])->get();
        return response()->json(['data'=>$techWorkHours]);
       
    }
}
