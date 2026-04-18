<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;


class ItemcategoryController extends Controller
{
    public function index()
{
    $categories = Category::with('parent', 'children')->get();
  
    return view('itemcategory.index', compact('categories'));
}
public function destroy($id)
{
    $categories = Category::find($id);
  
    $categories->delete();
    return redirect()->back()->with('success', __('category successfully deleted.'));
}

}