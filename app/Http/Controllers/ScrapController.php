<?php

namespace App\Http\Controllers;

use App\Models\SalesModule;
use App\Models\ScrapModule;
use Illuminate\Http\Request;

class ScrapController extends Controller
{
	public function moduleIndex()
	{
		$salesModule = SalesModule::first();

		$lastInvoice = SalesModule::orderBy('id', 'desc')->first();

		if ($lastInvoice) {
			$lastNumber = (int) preg_replace('/[^0-9]/', '', $lastInvoice->invoice_number);
			$nextNumber = $lastNumber + 1;
		} else {
			$nextNumber = 1;
		}

		// Format next invoice number: INV-0003
		$nextInvoiceNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

		return view('scrap.sales', compact('nextInvoiceNumber', 'salesModule'));
	}

	public function moduleStore(Request $request)
	{
		$request->validate([
			'prefix' => 'required|string|max:10',
			'invoice_number' => 'required|string',
			'invoice_page' => 'required|string',
			'invoice_name' => 'required|string',
			'invoice_arabic_name' => 'nullable|string',
			'invoice_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
			'terms' => 'nullable|string',
		]);

		$logoPath = null;
		if ($request->hasFile('invoice_logo')) {
			$logoPath = $request->file('invoice_logo')->store('invoice_logos', 'public');
		}

		if($request->id){
			SalesModule::where('id', $request->id)->update([
				'invoice_page' => $request->invoice_page,
				'invoice_name' => $request->invoice_name,
				'invoice_arabic_name' => $request->invoice_arabic_name,
				
				'terms' => $request->terms,
			]);
		}else{
			SalesModule::create([
				'invoice_number' => $request->prefix . '-' . $request->invoice_number,
				'invoice_page' => $request->invoice_page,
				'invoice_name' => $request->invoice_name,
				'invoice_arabic_name' => $request->invoice_arabic_name,
				'invoice_logo' => $logoPath,
				'terms' => $request->terms,
			]);
		}

		return redirect()->back()->with('success', 'Invoice update saved successfully!');
	}

	public function scrapIndex()
	{
		$scrapModule = ScrapModule::first();

		return view('scrap.scrap', compact('scrapModule'));
	}

	public function checkScrap(Request $request)
	{
		$scrap = ScrapModule::first();

		if (!$scrap) {
			$scrap = ScrapModule::create([
				'scrap_module' => $request->checked ? '1' : '0',
				'scrap_stock' => '1',
				'scrap_default' => '1'
			]);
		} else {
			$scrap->update([
				'scrap_module' => $request->checked ? '1' : '0'
			]);
		}

		return response()->json([
			'status' => filter_var($scrap->scrap_module, FILTER_VALIDATE_BOOLEAN)
		]);
	}

	public function checkScrapStock(Request $request)
	{
		$scrap = ScrapModule::first();

		$scrap->update([
			'scrap_stock' => $request->checked ? '1' : '0'
		]);

		return response()->json([
			'status' => filter_var($scrap->scrap_stock, FILTER_VALIDATE_BOOLEAN)
		]);
	}

	public function checkScrapDefault(Request $request)
	{
		$scrap = ScrapModule::first();

		$scrap->update([
			'scrap_default' => $request->checked ? '1' : '0'
		]);

		return response()->json([
			'status' => filter_var($scrap->scrap_default, FILTER_VALIDATE_BOOLEAN)
		]);
	}
}
