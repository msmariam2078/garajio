<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerTemplate;

class CustomerTemplateController extends Controller
{
    public function index()
    {       
        $customertemplate = CustomerTemplate::get();
        return view('customertemplate.index', compact('customertemplate'));
        
    }
}