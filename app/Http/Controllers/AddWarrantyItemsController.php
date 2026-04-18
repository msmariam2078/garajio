<?php

namespace App\Http\Controllers;

use App\Models\AddWarrantyItems;
use App\Models\WorkOrder;
use App\Models\Warranty_registration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AddWarrantyItemsController extends Controller
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
      
        $request->validate([
            'product_id' => 'required|integer|exists:service_parts,id',
            'workorder_id' => 'required|integer|exists:work_orders,id',
            'warrant_number' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
    
        $workOrder = WorkOrder::findOrFail($request->workorder_id);
    
        // Calculate the warranty period
        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date);
        $warrantyPeriod = $toDate->diffInMonths($fromDate); // Calculate the difference in days
    
        // Decode the vehicle ID (extract the integer from format "[50]")
        $decodedVehicleId = json_decode($workOrder->vehicle,true)[0] ?? null;

        //(int) str_replace(['[', ']'], '', $workOrder->vehicle); // Remove brackets and cast to int
        //dd($decodedVehicleId);
        $item=AddWarrantyItems::where('workorderid',$request->workorder_id)
        ->where('booking_item_id',$request->booking_item_id)->first();
        if($item)
        {
            $item->warrantynumber=$request->warrant_number;
            $item->save();
        }else{
        AddWarrantyItems::updateOrCreate([
            'product_id' => $request->product_id,
            'booking_item_id'=>$request->booking_item_id,
            'workorderid' => $request->workorder_id,
            'warrantynumber' => $request->warrant_number,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            // Store the calculated warranty period
        ]);}
    
        // Warranty_registration::create([
        //     'work_order_id' => $request->workorder_id,
        //     'product_id' => $request->product_id,
        //     'customer_id' => $workOrder->customer_id,
        //     'status' => 1,
        //     'vehicle' => $decodedVehicleId, // Use the decoded integer vehicle ID
        //     'warranty_start_date' => $request->from_date,
        //     'warranty_end_date' => $request->to_date,
        //     'warranty_period' => $warrantyPeriod
        // ]);
    
        return redirect()->back()->with('success', __('Warranty details created successfully.'));
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AddWarrantyItems  $addWarrantyItems
     * @return \Illuminate\Http\Response
     */
    public function show(AddWarrantyItems $addWarrantyItems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AddWarrantyItems  $addWarrantyItems
     * @return \Illuminate\Http\Response
     */
    public function edit(AddWarrantyItems $addWarrantyItems)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AddWarrantyItems  $addWarrantyItems
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AddWarrantyItems $addWarrantyItems)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AddWarrantyItems  $addWarrantyItems
     * @return \Illuminate\Http\Response
     */
    public function destroy(AddWarrantyItems $addWarrantyItems)
    {
        //
    }
}
