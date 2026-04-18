<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    public function index()
    {
		$origin = Origin::where('isDeleted', false)->get();

        return view('origin.index', compact('origin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('origin.create');
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
        'description' => 'required|string|max:255|unique:origins,description',
    ]);

    $brand = new Origin();
    $brand->description = $request->input('description');
    $brand->isDeleted = false;
    $brand->save();

    return redirect()->route('origin.index')->with('success', 'Origin created successfully!');
}

public function edit($id)
{
    $origin = Origin::findOrFail($id);
    return view('origin.edit', compact('origin'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'description' => 'required|string|max:255',
    ]);

    $brand = Origin::findOrFail($id);
    $brand->description = $request->input('description');
    $brand->isModified = true;
    $brand->save();

    return redirect()->route('origin.index')->with('success', 'Origin updated successfully!');
}

public function destroy($id)
{
    $brand = Origin::findOrFail($id);
	$brand->isDeleted = 1;
	$brand->save();

    return redirect()->route('origin.index')->with('success', 'Origin deleted successfully!');
}
}