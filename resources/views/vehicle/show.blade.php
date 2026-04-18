@extends('layouts.master')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/css/template.css')}}" rel="stylesheet" />



@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Equipment</h4><span class="text-muted p-2 m-0">/ Details</span>
        </div>
    </div>

    <div class="d-flex my-xl-auto right-content">
        <a href="{{ route ('vehicle.index') }}" class="btn btn-primary ml-2">
            <i class="fas fa-arrow-left"></i>
			Back
        </a>
    </div>

</div>
<!-- breadcrumb -->
@endsection
@section('content')
<div class=" bg-white shadow p-4">
    <div class="row m-auto alighn-items-center ">
        <!-- Rego -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group">
				<label class="form-label">Registration Number</label>
				<p class="form-control">{{ $vehicle->rego }}</p>
            </div>
        </div>

        <!-- Client -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group">
				<label class="form-label">Customer</label>
                <p class="form-control">{{ $vehicle->Client->first_name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- State -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">State/Emirates</label>
                <p class="form-control">{{ $vehicle->state }}</p>
            </div>
        </div>

        <!-- Vehicle Make -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Equipment Make</label>
                <p class="form-control">{{ $vehicle->vehicle_makes->make_name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Vehicle Model -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Equipment Model</label>
                <p class="form-control">{{ $vehicle->vehicle_models->model_name ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Vehicle Model Code -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Equipment Model Code</label>
                <p class="form-control">{{ $vehicle->vehicle_model_codes->model_code ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Model Series -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Model Series</label>
                <p class="form-control">{{ $vehicle->model_series }}</p>
            </div>
        </div>

        <!-- VIN -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">VIN</label>
                <p class="form-control">{{ $vehicle->vin }}</p>
            </div>
        </div>

       
        <!-- Vehicle Transmission -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Equipment Transmission</label>
                <p class="form-control">{{ $vehicle->vehicle_trans->transmission ?? 'N/A' }}</p>
            </div>
        </div>
       
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">A/C</label>
                <p class="form-control">{{ $vehicle->a_c ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        <!-- Vehicle Body Type -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Equipment Body Type</label>
                <p class="form-control">{{ $vehicle->vehicle_body_types->body_type ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Vehicle Colour -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Equipment Colour</label>
                <p class="form-control">{{ $vehicle->vehicle_colour_news->colour ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Vehicle Seating Capacity -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Equipment Seating Capacity</label>
                <p class="form-control">{{ $vehicle->vehicle_seats->seating_capacity ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Odometer -->
        <div class="col-md-4 col-lg-4 col-6">
            <div class="detail-group mb-4">
				<label class="form-label">Odometer</label>
                <p class="form-control">{{ $vehicle->odometer }}</p>
            </div>
        </div>
    </div>
</div>
@endsection