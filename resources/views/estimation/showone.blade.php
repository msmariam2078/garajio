@extends('layouts.master')

@section('css')
<!-- Bootstrap CSS -->
<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
<style>


</style>
@endsection

@section('page-header')
<!-- Breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Quotation</h4>
        </div>
    </div>
</div>
<!-- Breadcrumb -->
@endsection

@section('content')
<div class="container-fluid full-page-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Quotation #{{ $estimation->id }}</h4>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Due Date:</strong> {{ $estimation->due_date }}</p>
                    <p><strong>Booking ID:</strong> {{ $estimation->booking_id }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Customer Name:</strong> {{ $estimation->customer->first_name }} {{ $estimation->customer->last_name }}</p>
                </div>
            </div>

            <h5 class="mt-4">Booking Items</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>UOM</th>
                            <th>GST Price</th>
                            <th>Line Total</th>
                            <th>Warranty</th>
                            <th>Location</th>
                            <th>Tax Amount</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estimation->items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->uom }}</td>
                            <td>{{ ($item->gstprice) }}</td>
                            <td>{{ ($item->linetotal) }}</td>
                            <td>{{ $item->warrenty }}</td>
                            <td>{{ $item->location }}</td>
                            <td>{{ ($item->taxamount) }}</td>
                            <td>{{ ($item->totalamount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
