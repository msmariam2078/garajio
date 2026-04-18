<?php

namespace App\Http\Controllers;

use App\Models\WarHouse;
use App\Models\ServicePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServicePartadjustMent;

class ServicePartadjustMentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = Auth::user();
		if ($user->type == 'technician') {
			$adjustments = ServicePartadjustMent::with(['warehouse', 'servicePart'])
				->where('warehouse_id', $user->warehouse?->id)
				->selectRaw('GROUP_CONCAT(id ORDER BY id ASC) as ids, warehouse_id, service_part_id, sum(available) as available, sum(unavailable) as unavailable, sum(commited) as commited, sum(onhand) as onhand')
				->groupBy('warehouse_id', 'service_part_id')
				->get();
		} else {
			// Group by warehouse_id and service_part_id, then sum the available, unavailable, commited, and onhand
			$adjustments = ServicePartadjustMent::with(['warehouse', 'servicePart'])
				->selectRaw('GROUP_CONCAT(id ORDER BY id ASC) as ids, warehouse_id, service_part_id, sum(available) as available, sum(unavailable) as unavailable, sum(commited) as commited, sum(onhand) as onhand')
				->groupBy('warehouse_id', 'service_part_id')
				->get();
		}

		$warehouse = WarHouse::all();
		$servicePart = ServicePart::all();

		return view('servicepartavailabilty.index', compact('adjustments', 'warehouse', 'servicePart'));
	}

	public function adjustmentSearch(Request $request)
	{
		$user = Auth::user();

		$query = ServicePartadjustMent::with(['warehouse', 'servicePart'])
			->selectRaw('warehouse_id, service_part_id, sum(available) as available, sum(unavailable) as unavailable, sum(commited) as commited, sum(onhand) as onhand')
			->groupBy('warehouse_id', 'service_part_id');

		// Apply technician-specific filter
		if ($user->type == 'technician') {
			$query->where('warehouse_id', $user->warehouse?->id);
		}

		// Apply filters from request
		if ($request->filled('warehouse_id')) {
			$query->where('warehouse_id', $request->warehouse_id);
		}

		if ($request->filled('service_part_id')) {
			$query->where('service_part_id', $request->service_part_id);
		}

		$adjustments = $query->get();

		$warehouse = WarHouse::all();
		$servicePart = ServicePart::all();

		return view('servicepartavailabilty.index', compact('adjustments', 'warehouse', 'servicePart'));
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
	 * @param  \App\Models\ServicePartadjustMent  $servicePartadjustMent
	 * @return \Illuminate\Http\Response
	 */
	public function show(ServicePartadjustMent $servicePartadjustMent)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\ServicePartadjustMent  $servicePartadjustMent
	 * @return \Illuminate\Http\Response
	 */
	public function edit(ServicePartadjustMent $servicePartadjustMent)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\ServicePartadjustMent  $servicePartadjustMent
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, ServicePartadjustMent $servicePartadjustMent)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\ServicePartadjustMent  $servicePartadjustMent
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(ServicePartadjustMent $servicePartadjustMent)
	{
		//
	}
}
