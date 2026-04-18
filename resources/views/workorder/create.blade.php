@extends('layouts.master')
@section('css')


<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />




@endsection
@section('page-header')
<!-- breadcrumb -->
<style>
.pac-container {
    z-index: 10000 !important;
}

#map {
    height: 300px;
    width: 100%;
}
</style>
<div id="alertPlaceholder"></div>


<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">WorkOrder</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">
            @if (Gate::check('create client'))
            <a class="btn btn-primary btn-sm ml-20 " href="{{ route('booking.create') }}" data-size="lg"> <i
                    class="ti-plus mr-5"></i>
                {{ __('Create WorkOrder') }}
            </a>
            @endif
        </div>
    </div>

</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('messages_alert')
<!-- row opened -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Booking</div>
            </div>
            <div class="card-body ">
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="created_datetime">Created Date and Time</label>
                        <input type="datetime-local" id="created_datetime" name="created_date" class="form-control"
                            readonly>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter Subject"
                            required>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="customer_id">Customer</label>
                        <select id="customer_id" name="customer_id" class="form-control customer_id" required>
                            <option value="" disabled selected>Select Customer</option>
                            @foreach($clients as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>

                        @if (Gate::check('create client'))
                        <a class="customModal float-end small" href="#!" data-size="lg"
                            data-url="{{ route('client.create') }}" data-title="{{ __('Create Client') }}">
                            {{ __('Create Client') }}
                        </a>
                        @endif
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" id="customer_name" class="form-control" placeholder="Enter Customer Name"
                            readonly>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter Email" readonly>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="customer_type">Customer Type</label>
                        <input type="text" id="type" class="form-control" placeholder="Customer Type" readonly>
                    </div>

                    <input type="hidden" class="form-control" id="country" name="country" readonly>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="service_group" class="form-label">Service Group</label>
                        <select class="form-control select2 mt-2" name="service_group[]" id="service_group"
                            multiple="multiple">
                            <option value="">Select Service Group</option>
                            @foreach ($servicegroups as $servicegroup)
                            <option value="{{ $servicegroup->id }}">{{ $servicegroup->name }}</option>
                            @endforeach
                        </select>
                        <a class="customModal float-end small" href="#" data-size="lg"
                            data-url="{{ route('service.create') }}" data-title="{{ __('Add service') }}">
                            {{ __('Create service') }}
                        </a>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="inspection">Inspection</label>
                        <select id="inspection" name="inspection" class="form-control" required>
                            <option value="no" selected>No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>



                   

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="service_location" class="form-label">Service Address</label>
                        <input type="text" id="service_location" name="service_location" class="form-control mt-2"
                            placeholder="Service Address" readonly style="background-color: #e9ecef;">
                        <a class="float-end small" href="#" data-bs-toggle="modal" id="mapmodal_click"
                            data-bs-target="#mapmodal">
                            {{ __('Select Location on Map') }}
                        </a>
                    </div>



                  

                </div>
            </div>
        </div>
    </div>
</div>










@endsection






@section('js')



<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
<script src="{{URL::asset('assets/js/custom-script.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>





@endsection