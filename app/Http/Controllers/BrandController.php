<?php

namespace App\Http\Controllers;

use App\Models\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
  
    public function index()
    {
        $brand = Brand::where('isDeleted', false)->orWhereNull('isDeleted')->get();
        return view('brand.index', compact('brand'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('brand.create');
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
			'description' => 'required|string|max:255|unique:brands,description',
        ]);

        $brand = new Brand();
        $brand->description = $request->input('description');
        $brand->save();

        return redirect()->route('brand.index')->with('success', 'Brand created successfully!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->description = $request->input('description');
        $brand->isModified = true;
        $brand->save();

        return redirect()->route('brand.index')->with('success', 'Brand updated successfully!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->isDeleted = 1;
        $brand->save();

        return redirect()->route('brand.index')->with('success', 'Brand deleted successfully!');
    }
}