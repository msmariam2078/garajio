<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;

class ReportController extends Controller
{
    public function taxinvoice()
    {
        $invoices = Invoice::where('parent_id', parentId())->get();
        $clients = User::where('parent_id', parentId())->where('type', 'client')->get();
        $status = Invoice::$status;
        return view('report.taxinvoice', compact('invoices','clients',  'status'));
    }

    public function credit()
    {
      
       
     
        return view('report.credit');
    }

    public function paymentreport()
    {
        $payments = Payment::all();
        return view('report.payment', compact('payments'));
    }
}