<?php

namespace App\Http\Controllers;

use App\Models\WarHouse;
use Illuminate\Http\Request;
use App\Models\Inventory_setup;
use App\Models\InventoryDetail;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
	public function index()
	{
		$data['warehouse'] = WarHouse::all();
		$data['inventory'] = InventoryDetail::has('servicePart')->latest()->get();

		return view('inventory_details.index', $data);
	}

	public function inventorySearch(Request $request)
	{
		$query = InventoryDetail::with('servicePart');

		if ($request->filled('date')) {
			$query->whereDate('created_at', $request->date);
		}

		if ($request->filled('reference')) {
			$query->where('reference', $request->reference);
		}

		if ($request->filled('warehouse_id')) {
			$query->where('location', $request->warehouse_id);
		}

		if ($request->filled('item_type')) {
			$query->whereHas('servicePart', function ($q) use ($request) {
				$q->where('item_type', $request->item_type);
			});
		}

		$data['warehouse'] = WarHouse::all();

		$data['inventory'] = $query->has('servicePart')->latest()->get();

		return view('inventory_details.index', $data);
	}
	
	public function setup()
	{
		$setup = Inventory_setup::latest()->first() ?? null;
		return view('inventory_details.setup', compact('setup'));
	}
	public function savesetup(Request $request)
	{

		$setup = new Inventory_setup();
		$setup->auto = $request->auto == "on" ? 1 : 0;
		$setup->item_number = $request->item_number;
		$setup->item_prefix = $request->item_prefix;
		$setup->hand_availability = $request->hand_availability == "on" ? 1 : 0;
		$setup->save();
		return redirect()->back()->with('success', __('Inventory setup created successfully! '));
	}
}
