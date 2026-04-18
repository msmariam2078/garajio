@extends('layouts.master')
@section('title', 'Booking Create')
@section('css')
<style>
input[type="checkbox"] {
    width: 20px;
    height: 20px;
    appearance: none;
    
    border: 2px solid grey;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
}
.urgent:checked {
    background-color: DodgerBlue;
    border-color: DodgerBlue;
}
.completed:checked {
    background-color: DodgerBlue;
    border-color: DodgerBlue;
}
.fixed:checked {
    background-color: DodgerBlue;
    border-color: DodgerBlue;
}

.green{ 
    background-color: #d0f0c0;
}
.yellow{ 
    background-color: #fcffa4;
}
.red{ 
    background-color: #ff9999;
}
.green:checked {
    background-color: green;
    border-color: green;
}

.red:checked {
    background-color: red;
    border-color: red;
}
.yellow:checked {
    background-color: yellow;
    border-color: yellow;
}

input[type="checkbox"]::after {
    content: '✔'; /* Unicode checkmark */
    font-size: 16px;
    color: white; /* Checkmark color */
    position: absolute;
    top: 0;
    left: 3px;
    display: none;
}
input[type="checkbox"]:checked::after {
    display: block;
}
        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-top: 5px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
        }
    </style>

    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        .subrow {
            background-color: #f9f9f9;
        }

        .subrow td {
            padding: 10px;
        }

        .extend-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .table-hover tbody tr:hover {
            cursor: pointer;
        }
    </style>
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

        .hidden {
            display: none;
        }
    </style>
     <div class="modal" id="modaldemo1">
        <div class="modal-dialog modal-lg custom-modal" role="document" style="max-width: 1700px !important;">
            <div class="modal-content modal-content-demo">
                <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h6 class="modal-title">Available Technicians</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="width: auto;">
                            <label class="custom-switch pt-3">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="toggle-status" name="is_active" onchange="this.form.submit()">
                                <span class="slider round"></span>
                            </label>
                            <label class="pl-2">Filter with Vehicle</label>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="skill_group" class="form-label">Skill Group</label>
                            <select class="form-control select2 mt-2" name="skill_group[]" id="skill_group"
                                onchange="handleChange()">
                                <option value="">Select Skill Group</option>
                                @foreach ($skillgroups as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="button" id="checkin_button" class="btn btn-info"
                                onclick="handleButtonClick(2)">All</button>
                            <button type="button" id="checkin_button" class="btn btn-success"
                                onclick="handleButtonClick(1)">Online</button>
                            <button type="button" id="checkout_button" class="btn btn-danger"
                                onclick="handleButtonClick(0)">Offline</button>
                        </div>
                    </div>
                </div>

                <div class="modal-body" id="default-technicians">
                    <div class="table-responsive">
                        <table class="table text-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>Technician</th>
                                    <th>Availability</th>
                                    <th>Status</th>
                                    <th>Working Day</th>
                                    <th>Distance</th>
                                    <th>Working Period</th>
                                    <th>Book</th>
                                </tr>
                            </thead>
                            <tbody id="technicians-tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="quotationConfirmModal" tabindex="-1" aria-labelledby="quotationConfirmLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quotationConfirmLabel">Confirm Quotations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to confirm all quotations and proceed with the booking?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmSubmit" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div id="alertPlaceholder"></div>

    <div class="modal fade" id="mapmodal" tabindex="-1" aria-labelledby="mapmodalLabel" aria-hidden="true"
        style="background-color: #000000a8;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapmodalLabel">Please confirm location by dragging the marker on the map
                    </h5>
                    {{-- <button type="button" class="btn-close mapmodal_close" aria-label="Close"></button> --}}
                    <button type="button" class="close mapmodal_close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            {{ Form::label('service_address1', __('Address*'), ['class' => 'form-label']) }} <span
                                class="text-danger"></span>
                            {{ Form::text('service_address1', null, ['class' => 'form-control service_address', 'id' => 'service_address1', 'placeholder' => __('service address')]) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('service_city1', __('City*'), ['class' => 'form-label']) }} <span
                                class="text-danger"></span>
                            {{ Form::text('service_city1', null, ['class' => 'form-control service_city', 'placeholder' => __('service city')]) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('service_state1', __('State*'), ['class' => 'form-label']) }} <span
                                class="text-danger"></span>
                            {{ Form::text('service_state1', null, ['class' => 'form-control service_state', 'placeholder' => __('service state')]) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('service_country1', __('Country*'), ['class' => 'form-label']) }} <span
                                class="text-danger"></span>
                            {{ Form::text('service_country1', null, ['class' => 'form-control service_country', 'placeholder' => __('service country')]) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('service_zip_code1', __('Zip Code*'), ['class' => 'form-label']) }} <span
                                class="text-danger"></span>
                            {{ Form::text('service_zip_code1', null, ['class' => 'form-control service_zip_code', 'placeholder' => __('service zip code')]) }}
                        </div>
                        <div class="form-group col-md-12">
                            <div id="map"></div>
                        </div>
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mapmodal_close">Cancel</button>
                    <button type="button" class="btn btn-primary" id="mapmodal_confirm">Confirm</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function addCommentField() {
            const commentFields = document.getElementById('commentFields');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-2');
            newRow.innerHTML = `
            <div class="col-md-6">
                <input type="text" class="form-control comment" placeholder="Enter comment">
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-2 text-center">
                <button class="btn btn-danger btn-sm" type="button" onclick="removeField(this)">&times;</button>
            </div>`;
            commentFields.appendChild(newRow);
        }

        function removeField(button) {
            button.closest('.row').remove();
        }
    </script>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Booking</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                <a class="btn btn-primary ml-20 " href="{{ route('booking.index') }}" data-size="lg"> <i
                        class="ti-arrow-left"></i>
                    {{ __(' Booking') }}
                </a>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')
    <!-- row opened -->

    <div class="row">

        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3" id="searchGroup">
                        <h5 class="card-title mb-0 customer-header">Customer</h5>
                        {{-- <div class="input-group" style="max-width: 300px;">
                            <input type="text" class="form-control" placeholder="Searching....." id="searchCustomer">
                            <ul id="customerDropdown" class="dropdown-menu w-250"
                                style="display: none; position: absolute; top: 100%; left: 0; z-index: 1000;">
                            </ul>
                        </div> --}}

						<div class="input-group" style="max-width: 300px; position: relative;">
							<input type="text" class="form-control" placeholder="Searching....." id="searchCustomer" autocomplete="off">
							<ul id="customerDropdown" class="dropdown-menu w-100"
								style="display: none; position: absolute; top: 100%; left: 0; z-index: 1000;">
							</ul>
						</div>

                        <a class="customModal" href="#" data-size="lg" data-url="{{ route('booking.createclient') }}"
                            data-title="{{ __('Create Client') }}" style="font-size: 20px;">
                            <i class="ti-plus mr-5"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p id="noCustomer">No Customer Selected</p>
                    <div class="table-responsive" id="customerTable" style="display: none;">
                        <table class="table text-nowrap table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Client Type</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="customerTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Equipments</h5>
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" class="form-control" placeholder="Searching.....">
                        </div>

                        <a class="customModal" href="#" data-size="lg" data-url="{{ route('vehicle.create') }}"
                            data-title="{{ __('Create Equipment') }}" style="font-size: 20px;">
                            <i class="ti-plus mr-5"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p id="noVehicle">No Equipment Selected</p>
                    <div class="table-responsive" id="vehicleList" style="display: none;">
                        <table class="table text-nowrap table-bordered table-hover">
                            <thead>
                                <tr>
                                   <th scope="col">Reg No</th>
                                 
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Model Series</th>
                                    <th>Engine Number</th>
                                    <th>Obmeter</th>
                                    <th>Action</th>
                                    


                                </tr>
                            </thead>
                            <tbody id="vehicleTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Booking</div>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <input type="hidden" class="form-control" id="booking_id" readonly />
                        <input type="hidden" class="form-control" id="workorder_id" readonly />
                        <!-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="reference">Booking ID</label>
                           
                        </div>


                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="customerOrderNumber">Customer Order Number</label>
                            <input type="text" class="form-control" id="customerOrderNumber"
                                placeholder="Enter customer order number" readonly>
                        </div>  -->

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="bookingDate">Booking Date</label>
                            <input type="date" class="form-control" id="bookingDate" required>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="scheduled_time" class="form-label">Booking Time</label>
                            <input type="time" class="form-control mt-2" name="scheduled_time" id="booking_time"
                                placeholder="Enter scheduled Time">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="requestdate">Request Date</label>
                            <input type="date" class="form-control" id="requestdate" required>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="requesttime">Request Time</label>
                            <input type="time" class="form-control" id="requesttime" required>
                        </div>

                        <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="dueDate">Due Date</label>
                            <input type="date" class="form-control" id="dueDate" required>
                        </div> -->

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="country" class="form-label">Country</label>
                            {{ Form::select('country', $country, $country_s, ['class' => 'form-control basic-select pt-2', 'id' => 'country']) }}
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control mt-2" name="city" id="city"
                                placeholder="Enter City Name">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="service_location" class="form-label">Service Address</label>
                            <input type="text" id="service_location" name="service_location"
                                class="form-control mt-2" placeholder="Service Address" readonly
                                style="background-color: #e9ecef;">
                            <a class="float-end badge bg-primary text-light" href="#" data-bs-toggle="modal"
                                id="mapmodal_click" data-bs-target="#mapmodal">
                                {{ __('Select Location on Map') }}
                            </a>
                        </div>


                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="dueDate">LandMark</label>
                            <input type="text" class="form-control" id="landmark" required>
                        </div>


                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="service_group" class="form-label">Service Group</label>
                            <select class="form-control select2 mt-2" name="service_group[]" id="service_group"
                                multiple="multiple">
                                <option value="">Select Service Group</option>
                                @foreach ($servicegroups as $servicegroup)
                                    <option value="{{ $servicegroup->id }}">{{ $servicegroup->name }}</option>
                                @endforeach
                            </select>

                        </div>
                   

                        
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="skill_group" class="form-label">Skill Group</label>
                            <select class="form-control select2 mt-2" name="skill_group[]" id="service_group2"
                                multiple="multiple">
                                <option value="">Select Skill Group</option>
                                @foreach ($skillGroups as $skillGroup)
                                    <option value="{{ $skillGroup->id }}">{{ $skillGroup->group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3" placeholder="Enter description"></textarea>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="source">Source</label>
                            <select class="form-control" id='source' name='source'>
                                <option value='call'>Call
                                </option>
                                <option value='whatsapp'>Whatsapp
                                </option>
                                <option value='website'>Website
                                </option>
                                <option value='website'>Walkin
                                </option>

                            </select>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="exampleFormControlSelect1">Status</label>
                            <select class="form-control" name="status">
                                <option value="Booking">Booking</option>
                                <option value="Completed">Completed</option>
                                <option value="Quote">Quotation</option>
                                <option value="Quote">Cancelled</option>


                            </select>
                        </div>
                         <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="exampleFormControlSelect1">Task Priority</label>
                            <select class="form-control" name="task_priority">
                                <option value="Urgent">Urgent</option>
                                  <option value="fcfs">First Come First Serve</option> 
                                  <option value="Contract Customer">Contract Customer</option>     


                            </select>
                        </div>
                        <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="exampleFormControlSelect1">Technician</label>
                            <select class="form-control" name="technician">
                                  @foreach($technicians as $name => $id)
                                    <option value="{{$id}}">{{$name}}</option>
                                @endforeach


                            </select>
                        </div> -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="exampleFormControlSelect1">Supervisor</label>
                            <select class="form-control" name="supervisor">
                                   @foreach($supervisors as $name => $id)
                                    <option value="{{$id}}">{{$name}}</option>
                                @endforeach 


                            </select>
                        </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="exampleFormControlSelect1">Job Type</label>
                            <select class="form-control" name="job_type">
                              
                                  <option value="Warranty Repair">Warranty Repair</option>     
                                  <option value="Workshop Repair">Workshop Repair</option> 
                                  <option value="Pre-Delivery Inspection">Pre-Delivery Inspection</option> 
                                  <option value="Site Inspection">Site Inspection</option> 
                                  <option value="On-Site Repair">On-Site Repair</option> 
                                  <option value="On-Site Training">On-Site Training</option> 
                                  <option value="Equipment Audit">Equipment Audit</option> 
                                  <option value="Sharpening Service">Sharpening Service</option> 
                                  <option value="Re-Works">Re-Works</option> 


                            </select>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                            <label for="description">Remarks</label>
                            <textarea class="form-control" id="chatorremarks" rows="3" placeholder="Enter Chat or Remarks"></textarea>
                        </div>
                        <!-- <div class="card p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <label class="switch">Warrenty
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" data-toggle="toggle" data-size="sm" name="is_active"
                                            onchange="this.form.submit()">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div> -->



                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">

                            <input type="hidden" name="warrantystatus" id="warrantystatus" value="0">
                            <button class="btn btn-primary mt-4" id="toggleWarranty">Warranty</button>
                        </div>
                          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3 mt-3">
                                                
                            </div>


                    </div>
                </div>
            </div>
        </div>
     

    </div>
    <div class="row hidden" id="warranty-details-row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Warranty Details</div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Warranty No.</th>
                                <th>Work Order ID</th>
                                <th>Equipment</th>
                                <th>Customer ID</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Product Price</th>
                                <th>Warranty Period</th>
                                <th>Start Date</th>
                                <th>End Date</th>

                                <th>Claim Count</th>
                                <th>Jump Start</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="warranty-details-table-body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  <div class="row " id="">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Inspection Details

                    
                        
                         
                              <select class="float-right form-control w-50" id="inspection-temp">
                                <option>select A template</option>
                                @foreach($templates as $template)
                                <option value="{{$template->id}}">{{$template->code}}</option>
                              @endforeach
                              </select>
                         </div>
                  
                </div>
                <div class="card-body d-none" id="temp-details">
                   <div class="row">
                       <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Template Name</label>
                            <input type="text" class="form-control" id="temp-name" value="" readonly>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Template Description</label>
                            <input type="text" class="form-control" id="temp-des" value="" readonly>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Date</label>
                            <input type="date" class="form-control" id="temp-date" value="" readonly>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Vehicle Fault</label>
                            <input type="text" class="form-control" id="" value="" >
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Diagnostic Proccess</label>
                            <input type="text" class="form-control" id="" value="" >
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                            <label for="">Machine Hours</label>
                            <input type="text" class="form-control" id="machine_hours" value="" >
                        </div>
                   </div>
                </div>
                  <div id="mainAccordion" class="accordion"></div>
                    <!-- <div id="savebutton" class="d-none">
                        <button class="btn btn-primary float-right m-2 " >Save</button>
                    </div> -->

            </div>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-12">
            <div class="card"> -->
                <!-- <div class="card-header flex items-center justify-between">
                    <div class="card-title">Product Details</div>

                    <label class="custom-switch">
                        <input type="hidden" name="allproducts" id="allproducts" value="0">
                        <input type="checkbox" name="is_active" class="toggle-status" onchange="updateStatus(this)">
                        <span class="slider round"></span>
                    </label>
                </div> -->
                <script>
                    function updateStatus(checkbox) {
                        var statusInput = checkbox.closest('label').querySelector('input[name="allproducts"]');


                        if (checkbox.checked) {
                            statusInput.value = "1";
                        } else {
                            statusInput.value = "0";
                        }


                        checkbox.form.submit();
                    }
                </script>


                <!-- <div class="card-body"> -->
                    <!-- <div class="table-responsive">
                        <table class="table text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Type</th>
                                    <th>Product Id</th>
                                    <th>Product Name</th>
                                   
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>UOM</th>
                                    <th>Discount(%)</th>
                                    <th>Line Total</th>
                                    <th>Warranty</th>

                                    <th>Tax(%)</th>
                                    <th>Tax Amount</th>
                                    <th>Total Amount(Inc Tax Amount)</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody"></tbody>
                        </table>
                        <button type="button" class="btn btn-secondary btn-wave waves-effect waves-light"
                            id="addProduct">Add Product</button>

                    </div> -->


                    <!-- <div style="float: right; width: 35%; margin-top: 20px;"> -->
                        <!-- Collapsible Card -->
                        <!-- <div class="card custom-card collapse-card border shadow-lg">
                            <div
                                class="card-header d-flex align-items-center justify-content-between bg-primary text-white">
                                <div class="card-title mb-0 text-white">
                                    Order Summary
                                </div>
                                <a href="javascript:void(0);" data-bs-toggle="collapse"
                                    data-bs-target="#orderSummaryCollapse" aria-expanded="true"
                                    aria-controls="orderSummaryCollapse" class="text-white">
                                    <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                                    <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                                </a>
                            </div>
                            <div class="collapse show" id="orderSummaryCollapse">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Subtotal Excl. VAT:</strong></span>
                                        <span id="subtotalExclVAT">0.00</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span><strong>Discount Amount:</strong></span>
                                        <span id="discountAmount">0.00</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total Excl. VAT:</strong></span>
                                        <span id="totalExclVAT">0.00</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total VAT:</strong></span>
                                        <span id="totalVAT">0.00</span>
                                    </div>

                                    <div class="card-footer d-flex justify-content-between mt-3">
                                        <span><strong>Total Incl. VAT:</strong></span>
                                        <span id="totalInclVAT">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="btn-list mt-4 justify-content-right">
                            <div class="">
                                
                                       
                               
                                
                                
                                <button class="btn btn-danger btn-wave waves-effect waves-light  mb-2 float-right ml-2" type="button"
                                    id="cancelBooking">Cancel</button>
                                
                               <button class="btn  btn-wave waves-effect waves-light text-white mb-2 float-right ml-2" style="background:#31C950" type="button"
                                    id="submitBooking">WorkOrder</button>
                                <button class="btn  btn-wave waves-effect waves-light text-white mb-2 float-right d-none" style="background:#34A6F4" type="button"
                                    id="startquotation">Quotation</button>
                                <button class="btn  btn-wave waves-effect waves-light text-white mb-2 float-right d-none" style="background:#34A6F4" type="button"
                                    id="startinspection">Inspection</button>
                                <a class="btn  mb-2 float-right text-white ml-2" style="background:#34A6F4" data-target="#modaldemo1"
                                                        data-toggle="modal" href="#"
                                                        data-id="" id="startallocate">
                                                        Allocate Technicians
                                                    </a>
                                    <button class="btn btn-wave waves-effect waves-light text-white mb-2 float-right" style="background:#FE9A37" type="button"
                                    id="startBooking">Booking</button>
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> 




    <input type="hidden" id="selectedIds" name="selectedIds" readonly>
    <input type="hidden" id="selectedCustomerId" name="customer_id" />
    <input type="hidden" id="selectedVehicleId" name="vehicle_id" />
    <input type="hidden" id="city" name="city" />

    <input type="hidden" id="subrowData" name="subrowData" value="">
 <script>
        document.addEventListener("DOMContentLoaded", function() {
            const techniciansTbody = document.getElementById("technicians-tbody");

            function fetchTechnicians(checkinCheckout) {
                
                const workOrderId = document.getElementById("workorder_id").value;
                const skillGroup = document.getElementById("skill_group").value;
                console.log(workOrderId);
                const url =
                    `/gettechnicianallocate/${workOrderId}?checkin_checkout=${checkinCheckout}&skill_group=${skillGroup}`;

                console.log("Fetching technicians with URL:", url);

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        
                        if(!html.includes("404"))
                        {
                            techniciansTbody.innerHTML = html;

                            
                            
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching technicians:", error);
                        techniciansTbody.innerHTML = `
                    <tr>
                    
                        <td colspan="6" class="text-center text-danger">Failed to load data</td>
                    </tr>`;
                    });
            }


            window.handleButtonClick = function(value) {
                console.log("Button clicked. Sending value:", value);
                fetchTechnicians(value);
            };

            window.handleChange = function() {
                console.log("Skill group changed, fetching technicians.");
                fetchTechnicians(0);
            };

            fetchTechnicians(0);
        });
    </script>

    <script>
        document.getElementById('toggleWarranty').addEventListener('click', function() {
            const warrantyStatus = document.getElementById('warrantystatus');
            const warrantyDetailsRow = document.getElementById('warranty-details-row');

            if (warrantyStatus.value === "0") {
                warrantyStatus.value = "1";
                warrantyDetailsRow.classList.remove('hidden');
            } else {
                warrantyStatus.value = "0";
                warrantyDetailsRow.classList.add('hidden');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedIdsInput = document.getElementById('selectedIds');
            const subrowDataInput = document.getElementById('subrowData');
            let selectedIds = [];
            let subrowData = [];

            document.getElementById('toggleWarranty').addEventListener('click', function(event) {
                event.preventDefault();
                const customerId = document.getElementById('selectedCustomerId').value.trim();
                const vehicleId = document.getElementById('selectedVehicleId').value.trim();

                if (!customerId || !vehicleId) {
                    alert('Please enter both Customer ID and Equipment ID.');
                    return;
                }

                fetch(`/toggle-warranty/${customerId}/${vehicleId}`, {
                        method: 'GET'
                    })
                    .then((response) => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then((data) => {
                        const warrantyDetailsRow = document.getElementById('warranty-details-row');
                        const tableBody = document.getElementById('warranty-details-table-body');

                        if (data.warranty_registrations.length > 0) {
                            warrantyDetailsRow.classList.remove('hidden');
                            tableBody.innerHTML = '';

                            data.warranty_registrations.forEach((registration) => {
                                const product = registration.product || {};
                                const customer = registration.customer || {};
                                const vehicle = registration.vehicle || {};

                                const row = `
                <tr>
                    <td>${registration.id}</td>
                    <td>${registration.add_warranty_items ? registration.add_warranty_items.warrantynumber : ''}</td>
                    <td>#WO-${registration.work_order_id || 'N/A'}</td>
                    <td>${vehicle.rego || 'N/A'}</td>
                    <td>${customer.first_name || 'N/A'} ${customer.last_name || ''}</td>
                    <td>${product.product_name || 'N/A'}</td>
                    <td>${product.description || 'N/A'}</td>
                    <td>${product.price || 'N/A'}</td>
                    <td>${registration.warranty_period || 0} months</td>
                    <td>${registration.warranty_start_date || 'N/A'}</td>
                    <td>${registration.warranty_end_date || 'N/A'}</td>
                    <td>${registration.claim_count || 0}</td>
                    <td>${registration.jump_start || 'N/A'}</td>
                    <td>${registration.status === '1' ? 'Active' : 'Already Claimed'}</td>
                    <td>
                        <button class="btn btn-primary" data-id="${registration.id}" onclick="toggleWarrantySelection(this)">Add</button>
                        <button class="btn btn-primary extend-btn" data-id="${registration.id}" onclick="toggleWarrantySelection(this)">Extend</button>
                    </td>
                </tr>
            `;
                                tableBody.insertAdjacentHTML('beforeend', row);
                            });

                            // Attach event listeners for extend buttons
                            document.querySelectorAll('.extend-btn').forEach(button => {
                                button.addEventListener('click', function(event) {
                                    event.preventDefault();
                                    const id = button.getAttribute('data-id');
                                    const row = button.closest('tr');
                                    addSubrow(row, id);
                                });
                            });

                        } else {
                            warrantyDetailsRow.classList.add('hidden');
                            alert('No warranty registrations found.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('An error occurred while fetching warranty details. Please try again.');
                    });

            });

            window.toggleWarrantySelection = function(button) {
                const id = button.getAttribute('data-id');
                const index = selectedIds.indexOf(id);

                if (index === -1) {
                    selectedIds.push(id);
                    button.textContent = 'Remove';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-danger');
                    alert(`Warranty item with ID ${id} has been added.`);
                } else {
                    selectedIds.splice(index, 1);
                    button.textContent = 'Add';
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-primary');
                }
              
                selectedIdsInput.value = selectedIds.join(',');
                
            };

            function addSubrow(row, id) {
                const subrow = document.createElement('tr');
                subrow.classList.add('subrow');
                subrow.innerHTML = `
            <td colspan="12">
                <form class="extend-form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="title" placeholder="Enter Title" required>
                    </div>
                    <div class="form-group">
                        <label for="start-date">New Warranty Start Date:</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end-date">New Warranty End Date:</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary cancel-btn">Cancel</button>
                </form>
            </td>
        `;
                row.parentNode.insertBefore(subrow, row.nextSibling);

                const form = subrow.querySelector('.extend-form');
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const title = form.querySelector('[name="title"]').value;
                    const startDate = form.querySelector('[name="start_date"]').value;
                    const endDate = form.querySelector('[name="end_date"]').value;

                    const formData = {
                        id,
                        title,
                        startDate,
                        endDate
                    };
                    subrowData.push(formData);

                    subrowDataInput.value = JSON.stringify(subrowData);
                    alert(`Warranty for ID ${id} has been extended.`);


                });

                const cancelButton = subrow.querySelector('.cancel-btn');
                cancelButton.addEventListener('click', function() {
                    subrow.remove();
                });
            }
        });
    </script>

    <script>
         let insp =document.getElementById("inspection-temp");
     let name=document.getElementById('temp-name');
     let des=document.getElementById('temp-des');
     let date=document.getElementById('temp-date');
     let template;


     insp.addEventListener('change',  function() {
     document.getElementById("temp-details").classList.remove("d-none");
      const mainAccordion = document.getElementById('mainAccordion');
     mainAccordion.innerHTML='';
     let temp = this.value;
                $.ajax({
                    url: '/fetch-template-details?temp='+temp,
                    method: 'GET',
                    success: function(data) {
                    template=data;
                    console.log(template);
                    name.value=data.code || 'n/A';
                    des.value=data.des || 'n/A';
                    let d = new Date();
                    const yyyy = d.getFullYear();
                       const mm = String(d.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                       const dd = String(d.getDate()).padStart(2, '0');
                       date.value = `${yyyy}-${mm}-${dd}`;
                  
                    // const datetime= date.value=data.created_at;
                    // if(datetime)
                    // {
                    //      const dateObj = new Date(datetime);
                    //      const dateOnly = dateObj.toISOString().split('T')[0];
                    //      date.value=dateOnly;
                    // }
                    // const save =document.getElementById('savebutton');
                    // save.classList.remove('d-none');
                   
                    

  data.data.forEach((mainItem, mainIndex) => {
    // Create main card
    const mainCard = document.createElement('div');
    mainCard.classList.add('card');

    // Card header for main accordion item
    const mainHeader = document.createElement('div');
    mainHeader.classList.add('accor','bg-primary');
    mainHeader.id = `headingMain${mainIndex}`;

    mainHeader.innerHTML = `
      <h5 class="mb-0 d-flex justify-contnent-between">
        <button class="btn btn-link text-white" data-toggle="collapse" data-target="#collapseMain${mainIndex}" aria-expanded="false" aria-controls="collapseMain${mainIndex}">
         <span class="pr-4">${mainIndex+1}</span> ${mainItem.code}
              
        </button>
        

      </h5>
    `;

    // Collapse container for main accordion item
    const mainCollapse = document.createElement('div');
    mainCollapse.id = `collapseMain${mainIndex}`;
    mainCollapse.classList.add('collapse');
   // if (mainIndex === 0) mainCollapse.classList.add('show'); // open first by default
    mainCollapse.setAttribute('aria-labelledby', `headingMain${mainIndex}`);
    mainCollapse.setAttribute('data-parent', '#mainAccordion');

    // Nested accordion container inside mainCollapse
    const nestedAccordion = document.createElement('div');
    nestedAccordion.id = `nestedAccordion${mainIndex}`;
    nestedAccordion.classList.add('accordion');
    const nestedCard = document.createElement('div');


      nestedCard.classList.add('card','mt-3','p-2','bg-secondary-transparent','points');
        nestedCard.innerHTML = `<div class="row">
         <div class=" col-5 text-center">
            <label for="" class="">Description</label>
          </div>
          <div class=" col-1 text-center" >
            <label for="" class="font-weight-bold">Condition (E/G/F/P)</label>
          </div>
         
      
          <div class=" col-1 text-center">
            <label for="" class="">Maintenance Required</label>
          </div>
            <div class=" col-2 text-center" >
            <label for="" class="font-weight-bold">Comments</label>
          </div>
          <div class=" col-1 text-center">
          <label for="" class="">Completed</label>
            </div>
             <div class=" col-1 text-center">
          <label for="" class="">Fixed Soon</label>
            </div>
             <div class=" col-1 text-center">
          <label for="" class="">Urgent</label>
            </div>
   
                </div>`;
    // Loop through nested items
    mainItem.editpoints.forEach((nestedItem, nestedIndex) => {



    //   const nestedHeader = document.createElement('div');
    //   nestedHeader.classList.add('accor');
    //   nestedHeader.style.background='#eeeeee';
    //   nestedHeader.id = `headingNested${mainIndex}_${nestedIndex}`;

    //   nestedHeader.innerHTML = `
    //     <h6 class="m-0 p-0">
    //       <button class="btn btn-link m-0 pt-1 pl-3" data-toggle="collapse" data-target="#collapseNested${mainIndex}_${nestedIndex}" aria-expanded="false" aria-controls="collapseNested${mainIndex}_${nestedIndex}">
    //        <span class="pr-4">${nestedIndex+1}</span>${nestedItem.point_des}
    //       </button>
    
     
    //     </h6>
         
    //   `;

    //   const nestedCollapse = document.createElement('div');
    //   nestedCollapse.id = `collapseNested${mainIndex}_${nestedIndex}`;
    //   nestedCollapse.classList.add('collapse');
    //   nestedCollapse.setAttribute('aria-labelledby', `headingNested${mainIndex}_${nestedIndex}`);
    //   nestedCollapse.setAttribute('data-parent', `#nestedAccordion${mainIndex}`);


      nestedCard.innerHTML += `<div class="row p-2">
        <div class="col-5 text-center">
        <input type="hidden" class="point_id" name="point_group_id" value="${mainItem.id}"/>
            <input type="hidden" class="point_id" name="point_id" value="${nestedItem.id}"/>
             <input type="text" name="point_name" id="" value="${nestedItem.point_des}"class="form-control w-100" readonly>
          </div>
          <div class="col-1 text-center">
           
             <select name="point_condition" id="point_condition" class="form-control w-100 point_comment" >
             <option value=""></option>
             <option value="E">E</option>
             <option value="G">G</option>
             <option value="F">F</option>
             <option value="P">P</option>
             </select>
          </div>
      
          <div class=" col-1 text-center">

             <select name="point_maintenance" id="" class="form-control w-100 point_input" >
             <option value=""></option>
             <option value="Yes">Yes</option>
             <option value="No">No</option>
             </select>
          </div>
          <div class=" col-2 text-center">

             <input type="text" name="point_comment" id="" class="form-control w-100 point_input" >
          </div>
            
           
          <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed point_completed" name="point_completed" value="0"  />
            </div>
               <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed point_fixed" name="point_fixed" value="0"  />
            </div>
               <div class=" col-1 text-center">
          
            <input type="checkbox" class="completed point_urgent" name="point_urgent" value="0"  />
            </div>
        
                </div>`;

    //  nestedCard.appendChild(nestedHeader);
    //  nestedCard.appendChild(nestedCollapse);

    });
    nestedAccordion.appendChild(nestedCard);
    mainCollapse.appendChild(nestedAccordion);
    mainCard.appendChild(mainHeader);
    mainCard.appendChild(mainCollapse);

    mainAccordion.appendChild(mainCard);
  });
      
                    }
            });
            });
        let items = [];
        async function fetchSimilarProducts(query, itemType) {
            const allproducts = document.getElementById('allproducts').value;
            const vehicleId = document.getElementById('selectedVehicleId').value;
            try {
                const response = await fetch(
                    `/searchproducts?search=${encodeURIComponent(query)}&itemType=${encodeURIComponent(itemType)}&vehicle_id=${encodeURIComponent(vehicleId)}&allproducts=${encodeURIComponent(allproducts)}`
                );
                const products = await response.json();
                return products;
            } catch (error) {
                console.error('Error fetching similar products:', error);
                return [];
            }
        }

        async function fetchFirstProduct(vehicleId) {
            const allproducts = document.getElementById('allproducts').value;
            try {
                const response = await fetch(
                    `/searchproducts?vehicle_id=${encodeURIComponent(vehicleId)}&allproducts=${encodeURIComponent(allproducts)}`
                );
                const products = await response.json();
                console.log(products[0]);
                return products.length > 0 ? products[0] : null;
            } catch (error) {
                console.error('Error fetching the first product:', error);
                return null;
            }
        }


        document.getElementById('addProduct').addEventListener('click', async function() {
            const vehicleId = document.getElementById('selectedVehicleId').value;

            if (!vehicleId) {
                alert('Please select Equipment and customer first.');
                return;
            }

            const firstProduct = await fetchFirstProduct(vehicleId);
            if (firstProduct) {
                addProductToTable(firstProduct);
            }
        });

        function addProductToTable(productData) {
            if (!productData || !productData.id) {
                console.error("Invalid product data:", productData);
                return;
            }



            const unitPrice = parseFloat(productData.price) || 0;
            const quantity = 1;
            const discountPercentage = parseFloat(productData.discount_percentage) || productData.gstprice || 0;

            const discount = (quantity * unitPrice) * (discountPercentage / 100);
            const lineTotal = (unitPrice * quantity) - discount;

            const taxPercentage = parseFloat(productData.tax) || 0;
            const taxAmount = lineTotal * (taxPercentage / 100);
            const totalAmountIncTax = lineTotal + taxAmount;

            const item = {
                id: productData.id,
                productName: productData.product_name,
                item_no:productData.item_no||'',
                description: productData.type || '',
                unitPrice,
                quantity,
                uom: productData.uom,
                uom_name: productData.uom_name ,
                lineTotal,
                location:productData.warehouse_name,
                item_type: productData.item_type || '',
                discount_percentage: discountPercentage,
                warranty: productData.warranty || 'N/A',
                tax_percentage: taxPercentage,
                tax_amount: taxAmount.toFixed(3),
                total_amount_inc_tax: totalAmountIncTax.toFixed(3),
            };

            items.push(item);
            renderTable();
            updateSummary();
        }


        function renderTable() {
            console.log("Rendering Table with Items:", items); // Debugging
        
            const tableBody = document.getElementById('productTableBody');
            tableBody.innerHTML = '';

            items.forEach((item, index) => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
            <td>
                <button class="btn btn-danger btn-sm delete-row" data-index="${index}">Delete</button>
            </td>
            <td>
                <select class="item-type" id="item_type" data-index="${index}">
                    <option value="Inventory" ${item.item_type === 'Inventory' ? 'selected' : ''}>Inventory</option>
                    <option value="Service" ${item.item_type === 'Service' ? 'selected' : ''}>Service</option>
                    <option value="noninventory" ${item.item_type === 'noninventory' ? 'selected' : ''}>Non Inventory</option>
                </select>
            </td>
            <td class="item_no-cell" data-index="${index}">${item.item_no || ''}</td>
            <td class="product-name-cell" data-index="${index}">${item.productName}</td>

            <td class="unit-price-cell" data-index="${index}">${item.unitPrice.toFixed(3)}</td>
            <td class="qty-cell" data-index="${index}">${item.quantity}</td>
            <td class="qty-cell" data-index="${index}">${item.uom_name}</td>
            <td class="discount-percentage-cell" data-index="${index}">${item.discount_percentage ? `${item.discount_percentage}%` : 'N/A'}</td>
            <td class="line-total-cell" data-index="${index}">${item.lineTotal.toFixed(3)}</td>
            <td class="warranty-cell" data-index="${index}">${item.warranty}</td>
            <td>
                <input type="number" class="tax-percentage-cell" data-index="${index}" value="${item.tax_percentage}" min="0" max="100">
            </td>
            <td class="tax-amount-cell" data-index="${index}">${item.tax_amount}</td>
            <td class="total-amount-cell" data-index="${index}">${item.total_amount_inc_tax}</td>
            <input type="hidden" class="product-id" value="${item.id}" />
             <input type="hidden" class="location" value="${item.location}" />
            <input type="hidden" class="uom" value="${item.uom}" />
        `;
                tableBody.appendChild(newRow);
            });

            
            handleRowDelete();
            handleDoubleClickEdit();
            handleItemTypeChange();
        }


        function handleRowDelete() {
            document.querySelectorAll('.delete-row').forEach(function(button) {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    items.splice(index, 1);
                    renderTable();
                    updateSummary();
                });
            });
        }

        function handleItemTypeChange() {
            document.querySelectorAll('.item-type').forEach(function(select) {
                select.addEventListener('change', async function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const newItemType = this.value;

                    // Fetch the first product of the selected item type
                    const firstProduct = await fetchFirstProductByType(newItemType);

                    if (firstProduct) {
                        items[index].id = firstProduct.id;
                        items[index].productName = firstProduct.product_name;
                        items[index].location = firstProduct.warehouse_name;
                        items[index].item_no = firstProduct.item_no;
                        items[index].uom_name = firstProduct.uom_name;
                         items[index].uom = firstProduct.uom;
                        items[index].warranty = firstProduct.warranty || 'N/A';
                        items[index].description = firstProduct.type || '';
                        items[index].unitPrice = parseFloat(firstProduct.price) || 0;
                        items[index].discount_percentage = parseFloat(firstProduct
                            .discount_percentage) ||
                            0;
                        items[index].warranty = firstProduct.warranty || 'N/A';
                        items[index].item_type = newItemType;

                        // Recalculate the line total
                        const quantity = items[index].quantity;
                        const discount = (quantity * items[index].unitPrice) * (items[index].discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * quantity) - discount;

                        renderTable();
                        updateSummary();
                    }
                });
            });
        }

        async function fetchFirstProductByType(itemType) {
            try {
                const response = await fetch(`/searchproducts?itemType=${encodeURIComponent(itemType)}`);
                const products = await response.json();
                return products.length > 0 ? products[0] : null;
            } catch (error) {
                console.error('Error fetching the first product by type:', error);
                return null;
            }
        }


        function handleDoubleClickEdit() {
            // Product name editing
            document.querySelectorAll('.product-name-cell').forEach(function(cell) {
                cell.addEventListener('click', async function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentProductName = items[index].productName;
                    const currentItemType = items[index].item_type;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentProductName;
                    input.classList.add('product-input');

                    const dropdown = document.createElement('div');
                    dropdown.classList.add('dropdown-suggestions');
                    dropdown.style.position = 'absolute';
                    dropdown.style.backgroundColor = '#fff';
                    dropdown.style.border = '1px solid #ccc';
                    dropdown.style.boxShadow = '0px 4px 6px rgba(0, 0, 0, 0.1)';
                    dropdown.style.zIndex = '1000';
                    dropdown.style.maxHeight = '200px';
                    dropdown.style.overflowY = 'auto';
                    dropdown.style.width = 'auto';
                    dropdown.style.display = 'flex';
                    dropdown.style.flexDirection = 'column';

                    this.innerHTML = '';
                    this.appendChild(input);
                    this.appendChild(dropdown);
                    input.focus();

                    let selectedIndex = -1;

                    input.addEventListener('input', async function() {
                        const query = this.value.trim();
                        if (!query) {
                            dropdown.innerHTML = '';
                            return;
                        }

                        const similarProducts = await fetchSimilarProducts(query,
                            currentItemType);
                        dropdown.innerHTML = '';

                        // **Create table header**
                        const header = document.createElement('div');
                        header.style.display = 'flex';
                        header.style.fontWeight = 'bold';
                        header.style.padding = '8px';
                        header.style.backgroundColor = '#f8f9fa';
                        header.innerHTML = `<div style="width: auto; padding-right: 10px;">PRODUCT ID</div>
                                <div style="width: auto;">PRODUCT NAME</div>`;
                        dropdown.appendChild(header);

                        similarProducts.forEach((product, i) => {
                            const option = document.createElement('div');
                            option.classList.add('dropdown-option');
                            option.style.display = 'flex';
                            option.style.padding = '8px';
                            option.style.cursor = 'pointer';
                            option.style.borderBottom = '1px solid #eee';
                            option.innerHTML = `<div style="width: auto; padding-right: 10px;">${product.item_no}</div>
                                    <div style="width: 50%;">${product.product_name}</div>`;

                            option.addEventListener('mouseenter', () => {
                                document.querySelectorAll(
                                        '.dropdown-option')
                                    .forEach(opt => {
                                        opt.style.backgroundColor =
                                            '#fff';
                                        opt.style.color = '#000';
                                    });
                                option.style.backgroundColor = '#007bff';
                                option.style.color = '#fff';
                                selectedIndex = i;
                            });

                            option.addEventListener('click', function() {
                                updateSelectedProduct(index, product);
                            });

                            dropdown.appendChild(option);
                        });
                    });

                    input.addEventListener('keydown', function(event) {
                        const options = dropdown.querySelectorAll('.dropdown-option');
                        if (event.key === 'ArrowDown') {
                            selectedIndex = (selectedIndex + 1) % options.length;
                            highlightOption(options, selectedIndex);
                        } else if (event.key === 'ArrowUp') {
                            selectedIndex = (selectedIndex - 1 + options.length) % options
                                .length;
                            highlightOption(options, selectedIndex);
                        } else if (event.key === 'Enter' && selectedIndex >= 0) {
                            event.preventDefault();
                            updateSelectedProduct(index, similarProducts[selectedIndex]);
                        }
                    });

                    input.addEventListener('blur', function() {
                        setTimeout(() => renderTable(), 200);
                    });

                    function highlightOption(options, index) {
                        options.forEach(opt => {
                            opt.style.backgroundColor = '#fff';
                            opt.style.color = '#000';
                        });
                        options[index].style.backgroundColor = '#007bff';
                        options[index].style.color = '#fff';
                    }

                    function updateSelectedProduct(index, product) {
                        items[index].id = product.id;
                        items[index].productName = product.product_name;
                        items[index].item_no = product.item_no;
                        items[index].warranty = product.warranty || 'N/A';
                        items[index].location = product.warehouse_name;
                        items[index].uom_name= product.uom_name;
                         items[index].uom= product.uom;
                        items[index].description = product.type || '';
                        items[index].unitPrice = parseFloat(product.price) || 0;
                        items[index].item_type = product.item_type || 'standard';
                        items[index].discount_percentage = parseFloat(product.discount_percentage) || 0;

                        const discount = (items[index].quantity * items[index].unitPrice) * (items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * items[index].quantity) -
                            discount;
                        items[index].tax_percentage= parseFloat(product.tax) || 0;
                        items[index].tax_amount= items[index].lineTotal * (items[index].tax_percentage / 100);
                        
                        items[index].total_amount_inc_tax =items[index].lineTotal + items[index].tax_amount;
            

                        renderTable();
                        updateSummary();
                    }

                });
            });



            document.querySelectorAll('.description-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentDescription = items[index].description;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentDescription;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newDescription = this.value.trim();
                        items[index].description = newDescription;
                        renderTable();
                    });
                });
            });


            document.querySelectorAll('.unit-price-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentUnitPrice = items[index].unitPrice;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.value = currentUnitPrice;
                    input.classList.add('unit-price-input');

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newUnitPrice = parseFloat(this.value) || 0;
                        items[index].unitPrice = newUnitPrice;


                        const discount = (items[index].quantity * newUnitPrice) * (items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (newUnitPrice * items[index].quantity) - discount;


                        const taxAmount = items[index].lineTotal * (items[index].tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(3);
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(3);

                        renderTable();
                        updateSummary();
                    });
                });
            });


            // Quantity editing
            // Quantity editing
            document.querySelectorAll('.qty-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentQuantity = items[index].quantity;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.min = '1';
                    input.value = currentQuantity;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newQuantity = parseInt(this.value) || 1;
                        items[index].quantity = newQuantity;

                        // **Recalculate Line Total**
                        const discount = (newQuantity * items[index].unitPrice) * (items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * newQuantity) - discount;

                        // **Recalculate Tax Amount**
                        const taxAmount = items[index].lineTotal * (items[index].tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(3);

                        // **Recalculate Total Amount (Including Tax)**
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(3);

                        renderTable();
                        updateSummary();
                    });
                });
            });


            // Discount percentage editing
            document.querySelectorAll('.discount-percentage-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentDiscount = items[index].discount_percentage;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.min = '0';
                    input.max = '100';
                    input.value = currentDiscount;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newDiscount = parseFloat(this.value) || 0;
                        items[index].discount_percentage = newDiscount;

                        // **Recalculate Line Total**
                        const discount = (items[index].quantity * items[index].unitPrice) * (
                            newDiscount / 100);
                        items[index].lineTotal = (items[index].unitPrice * items[index].quantity) -
                            discount.toFixed(3);

                        // **Recalculate Tax Amount**
                        const taxAmount = items[index].lineTotal.toFixed(3) * (items[index].tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(3);

                        // **Recalculate Total Amount (Including Tax)**
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(3);

                        renderTable();
                        updateSummary();
                    });
                });
            });


            document.querySelectorAll('.warranty-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentWarranty = items[index].warranty;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentWarranty;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newWarranty = this.value.trim();
                        items[index].warranty = newWarranty;
                        renderTable();
                    });
                });
            });

            document.querySelectorAll('.line-total-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentLineTotal = items[index].lineTotal;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.min = '0';
                    input.value = currentLineTotal.toFixed(3);

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newLineTotal = parseFloat(this.value) || 0;
                        items[index].lineTotal = newLineTotal;

                        // Recalculate tax and total amount including tax
                        const taxPercentage = items[index].tax_percentage || 0;
                        const taxAmount = newLineTotal * (taxPercentage / 100);
                        items[index].tax_amount = taxAmount.toFixed(3);
                        items[index].total_amount_inc_tax = (newLineTotal + taxAmount).toFixed(3);

                        renderTable();
                        updateSummary();
                    });
                });
            });
            document.querySelectorAll('.tax-percentage-cell').forEach(input => {
                input.addEventListener('input', (event) => {
                    const index = event.target.getAttribute('data-index');
                    const newTaxPercentage = parseFloat(event.target.value) || 0;
                    const input = document.createElement('input');
                    input.type = 'text';

                    items[index].tax_percentage = newTaxPercentage;


                    const taxAmount = (items[index].lineTotal * newTaxPercentage) / 100;
                    items[index].tax_amount = taxAmount;
                    items[index].total_amount_inc_tax = items[index].lineTotal - taxAmount;


                    renderTable();
                });
            });
        }

        function updateSummary() {
            let subtotalExclVAT = 0;
            let discountAmount = 0;
            let totalVAT = 0;

            items.forEach(item => {
                subtotalExclVAT += (parseFloat(item.unitPrice) || 0) * (parseInt(item.quantity) || 1);
                discountAmount += ((parseFloat(item.unitPrice) || 0) * (parseInt(item.quantity) || 1)) * (
                    parseFloat(
                        item.discount_percentage) / 100 || 0);
                totalVAT += parseFloat(item.tax_amount) || 0;
            });

            let totalExclVAT = subtotalExclVAT - discountAmount;
            let totalInclVAT = totalExclVAT + totalVAT;

            // Update UI
            document.getElementById('subtotalExclVAT').innerText = subtotalExclVAT.toFixed(3);
            document.getElementById('discountAmount').innerText = discountAmount.toFixed(3);
            document.getElementById('totalExclVAT').innerText = totalExclVAT.toFixed(3);
            document.getElementById('totalVAT').innerText = totalVAT.toFixed(3);
            document.getElementById('totalInclVAT').innerText = totalInclVAT.toFixed(3);
        }
        // Update summary when freight input changes
        document.getElementById('freight').addEventListener('input', function() {
            updateSummary();
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#service_group').select2({
                placeholder: "Select Service Group",
                allowClear: true,
                width: '100%'
            });


            $('#service_group2').select2({
                placeholder: "Select Skill Group",
                allowClear: true,
                width: '100%'
            });


        });
    </script>
    <script>
        const searchInput = document.getElementById('searchCustomer');
        const customerDropdown = document.getElementById('customerDropdown');
        const noCustomer = document.getElementById('noCustomer');
        const customerTable = document.getElementById('customerTable');
        const customerTableBody = document.getElementById('customerTableBody');
        const searchGroup = document.getElementById('searchGroup');

        let customerId = "{{ session('customer_id') }}";
        let vehicleId = "{{ session('vehicle_id') }}";

        if (customerId) {
            fetchCustomerById(customerId);
        }

        if (vehicleId) {
            getVehicleDetails(vehicleId);
        }


        // function fetchCustomers(query) {
        //     fetch(`/clients/searchcustomer?query=${query}`)
        //         .then(response => response.json())
        //         .then(data => {

        //             // console.log(data);

        //             if (data.length > 0) {
        //                 populateDropdown(data);
        //             } else {
        //                 customerDropdown.innerHTML = '<li class="dropdown-item">No Customer Found</li>';
        //                 customerDropdown.style.display = 'block';
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Error fetching customers:', error);
        //         });
        // }

        function fetchCustomerById(customerId) {
            fetch(`/clients/getcustomer?id=${customerId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
						const fullPhone = `+${data.ccm.replace(/\D/g, '')}${data.phone_number}`;
                        selectCustomer(
                            data.id,
                            data.first_name,
                            data.last_name,
                            data.email,
                            fullPhone,
                            data.client_type,
                            data.service_city,
                            data.service_country,
                            data.service_address
                        );
                    }
                })
                .catch(error => {
                    console.error('Error fetching customer details:', error);
                });
        }

        // searchInput.addEventListener('input', (e) => {
        //     const query = e.target.value;
        //     if (query.length > 0) {
        //         fetchCustomers(query);
        //     } else {
        //         customerDropdown.style.display = 'none';
        //     }
        // });


	let typingTimer;
    const delay = 1000;

    function fetchCustomers(query) {
        fetch(`/clients/searchcustomer?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    populateDropdown(data);
                } else {
                    customerDropdown.innerHTML = '<li class="dropdown-item text-danger">No Customer Found</li>';
                    customerDropdown.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching customers:', error);
            });
    }

    function populateDropdown(customers) {
        customerDropdown.innerHTML = '';
        customers.forEach(customer => {
            const li = document.createElement('li');
            li.className = 'dropdown-item';
            li.textContent = `${customer.first_name} ${customer.last_name} (${customer.phone_number})`;
            li.dataset.id = customer.id;
            customerDropdown.appendChild(li);
        });
        customerDropdown.style.display = 'block';
    }

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        clearTimeout(typingTimer);
        if (query.length > 0) {
            typingTimer = setTimeout(() => {
                fetchCustomers(query);
            }, delay);
        } else {
            customerDropdown.style.display = 'none';
        }
    });

    // Optional: Hide dropdown when input loses focus
    searchInput.addEventListener('blur', () => {
        setTimeout(() => {
            customerDropdown.style.display = 'none';
        }, 200);
    });

    // Optional: Show dropdown again on focus if there's content
    searchInput.addEventListener('focus', () => {
        if (customerDropdown.children.length > 0) {
            customerDropdown.style.display = 'block';
        }
    });

    // Optional: Click on dropdown item
    customerDropdown.addEventListener('click', (e) => {
        if (e.target && e.target.matches('li')) {
            searchInput.value = e.target.textContent;
            customerDropdown.style.display = 'none';
        }
    });

        function selectCustomer(id, first_name, last_name, email, phone, client_type, service_city, service_country,
            service_address) {
            const fullName = `${first_name ? first_name : ''} ${last_name ? last_name : ''}`.trim();
            const displayName = fullName || 'No Name Available';

            const header = document.querySelector('.customer-header');
            header.innerHTML = displayName;

            // Hide search input and the `+` icon after selecting a customer
            document.getElementById('searchGroup').style.display = 'none';
            document.querySelector('.customModal').style.display = 'none';

            // Populate customer details
            customerTableBody.innerHTML = `
        <tr id="customerRow-${id}">
            <td>${id}</td>
            <td>${displayName}</td>
            <td>${client_type ? client_type : 'No Client Type Available'}</td>
            <td>${email ? email : 'No Email Available'}</td>
            <td>${phone ? phone : 'No Phone Available'}</td>
            <td>
                <button class="btn btn-danger py-1 px-2" onclick="removeCustomer(${id})">
                    Remove
                </button>
            </td>
        </tr>
    `;

            customerTable.style.display = 'block';
            noCustomer.style.display = 'none';
            document.getElementById('selectedCustomerId').value = id;

            document.getElementById('city').value = service_city || 'No City Available';
            document.getElementById('service_location').value = service_address || 'No location Available';


            getVehicle(id);
        }

        function removeCustomer(id) {
            // if (items.length > 0) {
            //     alert('Please remove all products before removing the customer.');
            //     return;
            // }
            const customerRow = document.getElementById(`customerRow-${id}`);
            if (customerRow) {
                customerRow.remove();
            }

            if (customerTableBody.children.length === 0) {
                customerTable.style.display = 'none';
                noCustomer.style.display = 'block';

                // Show search input and the `+` icon after removing a customer
                const searchGroup = document.getElementById('searchGroup');
                searchGroup.style.display = 'block';

                const searchInput = document.getElementById('searchCustomer');
                searchInput.style.display = 'block'; // Ensure input is visible
                searchInput.style.width = '100%'; // Set input width to full size
                searchInput.classList.add('form-control'); // Reapply Bootstrap class for styling
                searchInput.value = ''; // Clear the search input

                const header = document.querySelector('.customer-header');
                header.innerHTML = 'Select a customer';

                // Show the `+` icon again
                document.querySelector('.customModal').style.display =
                    'inline-block'; // Change to inline-block or block based on your layout
            }

            document.getElementById('vehicleList').style.display = 'none';
            document.getElementById('noVehicle').style.display = 'block';
            document.getElementById('noVehicle').textContent = 'No Vehicle Selected';

            vehicleListDisplay();
        }



        customerDropdown.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                // console.log('Clicked customer:', e.target);
                const customerId = e.target.getAttribute('data-id');
                const customerFirstName = e.target.getAttribute('data-first_name');
                const customerLastName = e.target.getAttribute('data-last_name');
                const customerEmail = e.target.getAttribute('data-email');
                const customerPhone = e.target.getAttribute('data-phone');
                const customerClientType = e.target.getAttribute('data-client_type');
                const customerServiceCity = e.target.getAttribute('data-service_city');
                const customerServiceCountry = e.target.getAttribute('data-service_country');
                const customerServiceAddress = e.target.getAttribute('data-service_address');
                selectCustomer(customerId, customerFirstName, customerLastName, customerEmail, customerPhone,
                    customerClientType, customerServiceCity, customerServiceCountry, customerServiceAddress);
                customerDropdown.style.display = 'none';
            }
        });

        function populateDropdown(customers) {
            let htmlOutput = '';
           
            customers.forEach(customer => {
                const firstName = customer.first_name || '';
                const lastName = customer.last_name || '';
                const email = customer.email || '';
                const ccm = customer.ccm;
                const codeOnly = ccm.match(/\(\+\d+\)/)?.[0].replace(/[()]/g, '') || '';
                const phoneNumber = customer.phone_number || 'No Phone Available';
                const clientType = customer.client_type || 'No Client Type';
                const serviceCity = customer.clients?.service_city || 'No City Available';
                const serviceCountry = customer.clients?.service_country || 'No Country Available';
                const serviceAddress = customer.clients?.service_address || 'no service location Available';

                let displayName = `${firstName} ${lastName}`.trim();
                if (!displayName) {
                    displayName = 'No Name Available';
                }

                htmlOutput += `
            <li class="dropdown-item">
                <a href="#" 
                   data-id="${customer.id}" 
                   data-first_name="${firstName}" 
                   data-last_name="${lastName}" 
                   data-email="${email}" 
                   data-phone="${codeOnly+phoneNumber}"
                   data-client_type="${clientType}"
                   data-service_city="${serviceCity}"
                   data-service_country="${serviceCountry}"
                   data-service_address="${serviceAddress}">
                    ${displayName} ${email ? `| ${email}` : ''} ${phoneNumber ? `| ${codeOnly+phoneNumber}` : ''} ${clientType ? `| ${clientType}` : ''}
                </a>
            </li>
        `;
            });

            customerDropdown.innerHTML = htmlOutput;
            customerDropdown.style.display = 'block';
        }






        function displayVehicles(vehicles) {
            const vehicleTableBody = document.getElementById('vehicleTableBody');
            const vehicleList = document.getElementById('vehicleList');
            const noVehicle = document.getElementById('noVehicle');

            vehicleTableBody.innerHTML = '';

            if (vehicles.length > 0) {
                let htmlOutput = '';
                vehicles.forEach(vehicle => {
                    htmlOutput += `
                    <tr class="vehicle-item" data-id="${vehicle.id}">                   
                    <td>${vehicle.rego || 'N/A'}</td>
                    <td>${vehicle.make_name || 'N/A'}</td>
                    <td>${vehicle.model_name || 'N/A'}</td>
                    <td>${vehicle.model_series || 'N/A'}</td>
                    <td>${vehicle.engine_number || 'N/A'}</td>
                    <td>${vehicle.obmeter || 'N/A'}</td>
                    <td>
                            <button class="btn btn-success py-1 px-2"">
                                Select
                            </button>
                        </td>
                </tr>
            `;
                });

                vehicleTableBody.innerHTML = htmlOutput;
                vehicleList.style.display = 'block';
                noVehicle.style.display = 'none';

                const vehicleItems = document.querySelectorAll('.vehicle-item');
                vehicleItems.forEach(item => {
                    item.addEventListener('click', () => {
                        const vehicleId = item.getAttribute('data-id');
                        getVehicleDetails(vehicleId);
                    });
                });
            } else {
                vehicleList.style.display = 'none';
                noVehicle.style.display = 'block';
                noVehicle.textContent = 'No Equipments Found';
            }
        }



        function getVehicle(clientId) {
            if (!clientId) {
                displayVehicles([]);
                return;
            }

            fetch(`/get-vehicle?client_id=${clientId}`)
                .then(response => response.json())
                .then(data => {
                    displayVehicles(data);
                })
                .catch(error => {
                    console.error('Error fetching Equipments:', error);
                });
        }

        function getVehicleDetails(vehicleId) {
            fetch(`/get-vehicle-details/${vehicleId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(vehicle => {
                    if (vehicle) {
                        const vehicleTableBody = document.getElementById('vehicleTableBody');
                        const vehicleList = document.getElementById('vehicleList');
                        const noVehicle = document.getElementById('noVehicle');
                        const selectedVehicleInput = document.getElementById('selectedVehicleId');

                        vehicleTableBody.innerHTML = `
                    <tr id="vehicleRow-${vehicle.id}">
                        <td>${vehicle.rego || 'N/A'}</td>
                        <td>${vehicle.make_name || 'N/A'}</td>
                        <td>${vehicle.model_name || 'N/A'}</td>
                        <td>${vehicle.model_series || 'N/A'}</td>
                        <td>${vehicle.engine_number || 'N/A'}</td>
                         <td>${vehicle.obometer || 'N/A'}</td>
                       
                        
                        <td>
                            <button class="btn btn-danger py-1 px-2" onclick="removeVehicle(${vehicle.id})">
                                Remove
                            </button>
                        </td>
                    </tr>
                `;

                        vehicleList.style.display = 'block';
                        noVehicle.style.display = 'none';

                        // Store the selected vehicle ID in the input field
                        selectedVehicleInput.value = vehicle.id;
                    }
                })
                .catch(error => {
                    console.error('Error fetching Equipment details:', error);
                });
        }

        function vehicleListDisplay() {
            const vehicleList = document.getElementById('vehicleList');
            vehicleList.innerHTML = '';

            vehicleList.innerHTML = `
        <table class="table text-nowrap table-bordered table-hover abc2">
            <thead>
                <tr>
                    <th scope="col">Reg No</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Model Series</th>
                    <th>Engine Number</th>
                     <th>Obmeter</th>
                    <th>Action</th>
                   
                </tr>
            </thead>
            <tbody id="vehicleTableBody"></tbody>
        </table>
    `;
        }


        function removeVehicle(vehicleId) {
            // Remove the selected vehicle from the UI
            const vehicleRow = document.getElementById(`vehicleRow-${vehicleId}`);
            if (vehicleRow) {
                vehicleRow.remove();
            }

            // Hide the vehicle details section and show the vehicle list again
            document.getElementById('vehicleList').style.display = 'none';
            document.getElementById('noVehicle').style.display = 'block';
            document.getElementById('noVehicle').textContent = 'No Equipment Selected';

            // Clear the selected vehicle input field
            document.getElementById('selectedVehicleId').value = '';

            // Fetch and display the updated vehicle list for the selected customer
            const customerId = document.getElementById('selectedCustomerId').value;
            if (customerId) {
                getVehicle(customerId); // Re-fetch vehicles for the selected customer
            }
        }
        // function removeVehicle(vehicleId) {
        //     const vehicleRow = document.getElementById(`vehicleRow-${vehicleId}`);   
        //     if (vehicleRow) {
        //         vehicleRow.remove();
        //     }

        //     const vehicleList = document.getElementById('vehicleList');
        //     const noVehicle = document.getElementById('noVehicle');

        //     if (vehicleList.querySelectorAll('tbody tr').length === 0) {
        //         vehicleList.style.display = 'none';
        //         noVehicle.style.display = 'block';
        //         noVehicle.textContent = 'No Vehicle Selected';
        //     }

        //     document.getElementById('selectedVehicleId').value = '';
        // }
    </script>
    <script>
        const currentDate = new Date();

        // Set initial values for bookingDate, bookingTime, requestdate, and requesttime
        const bookingDateInput = document.getElementById('bookingDate');
        bookingDateInput.value = currentDate.toISOString().split('T')[0];

        const bookingTimeInput = document.getElementById('booking_time');
        const currentHours = String(currentDate.getHours()).padStart(2, '0');
        const currentMinutes = String(currentDate.getMinutes()).padStart(2, '0');
        bookingTimeInput.value = `${currentHours}:${currentMinutes}`;

        const requestDateInput = document.getElementById('requestdate');
        requestDateInput.value = currentDate.toISOString().split('T')[0];

        const requestTimeInput = document.getElementById('requesttime');
        let requestTime = new Date();
        requestTime.setMinutes(requestTime.getMinutes() + 30);
        const requestHours = String(requestTime.getHours()).padStart(2, '0');
        const requestMinutes = String(requestTime.getMinutes()).padStart(2, '0');
        requestTimeInput.value = `${requestHours}:${requestMinutes}`;

        // Set initial and dynamically updated min attribute for requestdate
        function setMinRequestDate() {
            const bookingDate = new Date(bookingDateInput.value);
            requestDateInput.setAttribute('min', bookingDate.toISOString().split('T')[0]);
        }
        setMinRequestDate();

        bookingDateInput.addEventListener('change', setMinRequestDate);

        // Ensure requesttime is not earlier than booking_time
        function setMinRequestTime() {
            const bookingTimeParts = bookingTimeInput.value.split(':');
            const bookingTimeDate = new Date();
            bookingTimeDate.setHours(bookingTimeParts[0]);
            bookingTimeDate.setMinutes(bookingTimeParts[1]);

            const requestTimeParts = requestTimeInput.value.split(':');
            const requestTimeDate = new Date();
            requestTimeDate.setHours(requestTimeParts[0]);
            requestTimeDate.setMinutes(requestTimeParts[1]);

            if (requestTimeDate < bookingTimeDate) {
                requestTimeInput.value = bookingTimeInput.value;
                alert("Request time cannot be earlier than the booking time.");
            }
        }

        bookingTimeInput.addEventListener('change', () => {
            setMinRequestTime();

            let newRequestTime = new Date();
            newRequestTime.setHours(parseInt(bookingTimeInput.value.split(':')[0]));
            newRequestTime.setMinutes(parseInt(bookingTimeInput.value.split(':')[1]) + 30);
            const newRequestHours = String(newRequestTime.getHours()).padStart(2, '0');
            const newRequestMinutes = String(newRequestTime.getMinutes()).padStart(2, '0');
            requestTimeInput.value = `${newRequestHours}:${newRequestMinutes}`;
        });

        requestTimeInput.addEventListener('change', setMinRequestTime);

        // Handle due date
        const dueDateInput = document.getElementById('dueDate');

        function setDueDate() {
            const requestDate = new Date(requestDateInput.value);
            if (!isNaN(requestDate.getTime())) {
                requestDate.setDate(requestDate.getDate() + 1);
                dueDateInput.value = requestDate.toISOString().split('T')[0];
            }
        }

        // Set initial due date
        setDueDate();

        // Update due date when requestdate changes
        requestDateInput.addEventListener('change', setDueDate);
    </script>

    <script>
        $('.mapmodal_close').on('click', function() {
            $('#mapmodal').modal('hide');
        });

        $('#mapmodal_confirm').on('click', function() {
            $('#mapmodal').modal('hide');

            document.getElementById('service_location').value = document.getElementById('service_address1').value;
            document.getElementById('city').value = document.getElementById('service_city1').value;
            // document.getElementById('service_state').value = document.getElementById('service_state1').value;
            // document.getElementById('service_country').value = document.getElementById('service_country1').value;
            // document.getElementById('service_zip_code').value = document.getElementById('service_zip_code1').value;
        });

        $('.numberonly').keypress(function(e) {

            var charCode = (e.which) ? e.which : event.keyCode

            if (String.fromCharCode(charCode).match(/[^0-9]/g))

                return false;

        });

        let map; // Declare map and marker at the top level
        let marker;

        $('#mapmodal').on('shown.bs.modal', function() {
            initAutocomplete_map();
            google.maps.event.trigger(map, 'resize');
            if (marker) {
                map.setCenter(marker.getPosition());
            }
        });

        $('#mapmodal_click').on('click', function() {
            document.getElementById('service_address1').value = document.getElementById('service_location').value;
            document.getElementById('service_city1').value = document.getElementById('city').value;

            initAutocomplete_map();
        });

        var lat2, lng2;

        function initAutocomplete_map() {
            var address = document.getElementById('service_location').value;

            if (address == '') {
                address = 'Dubai';
            }

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status === 'OK') {
                    var lat2 = results[0].geometry.location.lat();
                    var lng2 = results[0].geometry.location.lng();
                }
            });

            // Initialize map
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: lat2,
                    lng: lng2
                },
                zoom: 12
            });

            // Initialize marker
            var marker = new google.maps.Marker({
                position: map.getCenter(),
                map: map,
                draggable: true
            });

            // Geocode the default address to set map center and marker
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status === 'OK') {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    // Optionally, fill in the form fields with address components
                    fillAddressComponents(results[0].address_components);
                }
            });

            // Autocomplete input field
            var input = document.getElementById('service_address1');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                document.getElementById('service_city1').value = '';
                document.getElementById('service_state1').value = '';
                document.getElementById('service_country1').value = '';
                document.getElementById('service_zip_code1').value = '';
                document.getElementById('service_address1').value = place.formatted_address;

                // Set address components
                place.address_components.forEach(function(component) {
                    var types = component.types;
                    if (types.includes('locality')) {
                        document.getElementById('service_city1').value = component.long_name;
                    } else if (types.includes('administrative_area_level_1')) {
                        document.getElementById('service_state1').value = component.short_name;
                    } else if (types.includes('country')) {
                        document.getElementById('service_country1').value = component.long_name;
                    } else if (types.includes('postal_code')) {
                        document.getElementById('service_zip_code1').value = component.long_name;
                    }
                });

                // Update marker and map center based on the new location
                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                marker.setPosition({
                    lat: lat,
                    lng: lng
                });
                map.setCenter({
                    lat: lat,
                    lng: lng
                });
            });

            // Listener for marker drag event to update address components
            google.maps.event.addListener(marker, 'dragend', function() {
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Reverse geocode to get address components
                geocoder.geocode({
                    'location': {
                        lat: lat,
                        lng: lng
                    }
                }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        document.getElementById('service_address1').value = results[0].formatted_address;
                        fillAddressComponents(results[0].address_components);
                    }
                });
            });

            // Prevent form submission on Enter key press
            input.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('m_crc');
            const choices = new Choices(element, {
                removeItemButton: true,
                maxItemCount: -1,
                searchResultLimit: 5,
                renderSelectedChoices: 'always'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#customer_id').select2({
                placeholder: 'Select your customer',
                allowClear: true
            });

            $('#customer_id').on('change', function() {
                var customerId = this.value;
                if (customerId) {
                    fetch(`/workorder-client/fetch/api/${customerId}`)
                        .then(response => response.json())
                        .then(data => {

                            $('#customer_name').val(data.full_name);
                            $('#phone').val(data.phone);
                            $('#email').val(data.email);
                            $('#type').val(data.type);


                            $('#country').val(data.country_id).trigger(
                                'change');
                        })
                        .catch(error => console.error('Error:', error));
                } else {

                    $('#customer_name').val('');
                    $('#phone').val('');
                    $('#email').val('');
                    $('#type').val('');
                    $('#country').val('').trigger('change');
                }
            });
        });


        $('#service_location').on('input', function() {
            var address = $(this).val();
            if (address.length > 1) {
                $.ajax({
                    url: '/fetch-place',
                    method: 'GET',
                    data: {
                        address: address
                    },
                    success: function(data) {
                        var suggestions = $('#addressSuggestions');
                        suggestions.empty();
                        if (data.candidates && data.candidates.length > 0) {
                            suggestions.show();
                            data.candidates.forEach(function(place) {
                                var listItem = $('<li class="list-group-item"></li>')
                                    .text(place.formatted_address)
                                    .data('address', place.formatted_address)
                                    .on('click', function() {
                                        $('#service_location').val($(this).data(
                                            'address'));
                                        suggestions.hide();
                                    });
                                suggestions.append(listItem);
                            });
                        } else {
                            suggestions.hide();
                        }
                    },
                    error: function() {
                        alert("Error fetching address.");
                    }
                });
            } else {
                $('#addressSuggestions').hide();
            }
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#service_location').length) {
                $('#addressSuggestions').hide();
            }
        });
    </script>

    <script>
        $(document).ready(function() {

            function validateFormData(formData) {
                let errors = [];

                if (!formData.service_group) {
                    errors.push("Service Group is required.");
                }
                // if (!formData.skill_group) {
                //     errors.push("Skill Group is required.");
                // }
                if (!formData.service_location) {
                    errors.push("Service Address is required.");
                }

                if (!formData.customer_id) {
                    errors.push("Customer is required.");
                }



                // if (formData.products.length === 0) {
                //     errors.push("At least one product must be added.");
                // }

                return errors;
            }

            function collectFormData() { 
                let formData = {
                    reference: $('#reference').val(),
                    customerOrderNumber: $('#customerOrderNumber').val(),
                    bookingDate: $('#bookingDate').val(),
                    bookingTime: $('#booking_time').val(),
                    dueDate: $('#dueDate').val(),
                    requestdate: $('#requestdate').val(),
                    requesttime: $('#requesttime').val(),
                    description: $('#description').val(),
                    service_group: $('select[name="service_group[]"]').val(),
                    skill_group: $('select[name="skill_group[]"]').val(),
                    city: $('#city').val(),
                    country: $('#country').val(),
                    service_location: $('#service_location').val(),
                    status: $('select[name="status"]').val(),
                    job_type: $('select[name="job_type"]').val(),
                    task_priority: $('select[name="task_priority"]').val(),
                    technician: $('select[name="technician"]').val(),
                    supervisor: $('select[name="supervisor"]').val(),
                    source: $('select[name="source"]').val(),
                    freight: $('#freight').val(),
                    subtotal: $('#subtotal').text(),
                    gst: $('#gst').text(),
                    landmark: $('#landmark').val(),
                    total: $('#total').text(),
                    products: collectProductDetails(),
                    points:collectPoints(),
                    customer_id: $('#selectedCustomerId').val(),
                    vehicle_id: $('#selectedVehicleId').val(),
                    comments: collectComments(),
                    warrantyregisterationselectedIds: $('#selectedIds').val(),
                    subrowdata: $('#subrowData').val(),
                    template:template,
                    machine_hours:$('#machine_hours').val(),
                    // Fixed comment collection here
                };

                return formData;
            }
            function collectPoints()
            {const points = [];
                const accordionRoot = document.querySelectorAll('.points .row');
                accordionRoot.forEach(e=>{
                const inputs = e.querySelectorAll('input,select');
                console.log(inputs);
                
                let point={};
                inputs.forEach(input => {
                
                 const key = input.name ;
  
               if (input.matches("select")) {
                   point[key] =  input.value;
                          }
                          else if (input.type === 'checkbox'){
                             point[key] = input.checked;

                          } else {
                     point[key] = input.value;
                }
                if(point[key]==='point_red'){}
                 
                    });
                    points.push(point);
                });
                 return points;
            }
            function collectComments() {
                let comments = [];
                $('#commentFields .row').each(function() {
                    let comment = $(this).find('.comment').val();
                    let date = $(this).find('.date').val();
                    comments.push({
                        comment,
                        date
                    });
                });
                return comments;
            }



            function collectComments() {
                let comments = [];
                $('#commentFields .row').each(function() {
                    let comment = $(this).find('.comment').val();
                    let date = $(this).find('.date').val();
                    comments.push({
                        comment,
                        date
                    });
                });
                return comments;
            }


            function collectProductDetails() {
                let products = [];

                $('#productTableBody tr').each(function() {
                    let product = {
                        product_id: $(this).find('.product-id')
                            .val(), // Retrieve the hidden product ID
                        warehouse_id: $(this).find('.location')
                            .val(), 
                        uom: $(this).find('.uom')
                            .val(),   
                        item_no: $(this).find('td:eq(2)').text(),
                        item_type: $(this).find('#item_type').val(),
                        product_name: $(this).find('td:eq(3)').text(),
           
                        unit_price: $(this).find('td:eq(4)').text(),
                        quantity: $(this).find('td:eq(5)').text(),
                        uom_name: $(this).find('td:eq(6)').text(),
                        gst: $(this).find('td:eq(7)').text(),
                        line_total: $(this).find('td:eq(8)').text(),
                        warranty: $(this).find('td:eq(9)').text(),
                        taxpercentage: parseFloat($(this).find('.tax-percentage-cell').val()) || 0,
                        taxamount: $(this).find('td:eq(11)').text(),
                        totalamount: $(this).find('td:eq(12)').text(),

                    };
                    products.push(product);
                });

                return products;
            }


            function showBootstrapAlert(messages) {
                let alertHTML = `
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Please check the following fields:<br>
            ${messages.map(msg => `- ${msg}`).join('<br>')}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
                $('#alertPlaceholder').html(alertHTML);

                // Scroll to the top of the page
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth' // Smooth scroll effect
                });
            }
            //sdfsdfsfsdfsdf
            // $('#submitBooking').click(function(e) {
            //     e.preventDefault();

            //     let formData = collectFormData();
            //     let errors = validateFormData(formData);

            //     if (errors.length > 0) {
            //         showBootstrapAlert(errors);
            //         return;
            //     }

            //     $.ajax({
            //         url: "{{ route('booking.store') }}",
            //         method: 'POST',
            //         data: JSON.stringify(formData),
            //         contentType: 'application/json',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             alert('Booking submitted successfully!');

            //             window.location.href = "{{ url('workorder') }}/" + response
            //                 .workOrderId +
            //                 "/edit";
            //         },
            //         error: function(error) {FQuota
            //             alert('Error submitting booking');
            //             console.error(error);
            //         }
            //     });
            // });

            // $('#submitBooking').click(function(e) {
            //     e.preventDefault();
            //     $('#quotationConfirmModal').modal('show');
            // });
                document.getElementById("submitBooking").addEventListener("click", function(e) {
            e.preventDefault();


            let bookingId = document.getElementById("booking_id").value;


            if (bookingId) {
                let workorderId = document.getElementById("workorder_id").value;

                window.location.href = "{{ url('workorder') }}/" + workorderId + "/edit";
            } else {

                alert('Create a booking first!');
            }
        });

            $('#confirmSubmit').click(function() {
                let formData = collectFormData();
                let errors = validateFormData(formData);

                if (errors.length > 0) {
                    showBootstrapAlert(errors);
                    $('#quotationConfirmModal').modal('hide');
                    return;
                }

                $.ajax({
                    url: "{{ route('booking.store') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Booking submitted successfully!');
                        window.location.href = "{{ url('workorder') }}/" + response.workorder +
                            "/edit";
                        // $('#booking_id').val(response.booking_id);
                        // $('.card-title').text(`Booking [${response.booking_id}]`);
                    },
                    error: function(error) {
                        alert('Error submitting booking');
                        console.error(error);
                    }
                });

                $('#quotationConfirmModal').modal('hide');
            });
            $('#startBooking').click(function(e) {
                e.preventDefault();

                let formData = collectFormData();
                let errors = validateFormData(formData);

                if (errors.length > 0) {
                    showBootstrapAlert(errors);
                    return;
                }

                $.ajax({
                    url: "{{ route('booking.start') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Booking created successfully!');
                        $('#booking_id').val(response.booking_id);
                        $('#workorder_id').val(response.workorder);
                        const element = document.getElementById("startallocate");
                        element.setAttribute("data-id", response.workorder);
                        // $('.card-title').text(`Booking [${response.booking_id}]`);
                    },
                    error: function(error) {
                        alert('Error starting booking');
                        console.error(error);
                    }
                });
            });





        });


        // document.getElementById('allocateTechnicianButton').addEventListener('click', function(e) {
        //     e.preventDefault(); // Prevent the default button action

        //     // Get the values of the required fields
        //     const serviceGroup = document.querySelector('select[name="service_group[]"]');
        //     const serviceLocation = document.querySelector('#service_location');

        //     // Initialize an array to hold error messages
        //     let messages = [];

        //     // Validate fields
        //     if (!serviceGroup || serviceGroup.selectedOptions.length === 0) {
        //         messages.push("Service Group is required.");
        //     }

        //     if (!serviceLocation || serviceLocation.value.trim() === "") {
        //         messages.push("Service Address is required.");
        //     }

        //     // If there are errors, show alert
        //     if (messages.length > 0) {
        //         showBootstrapAlert(messages);
        //     } else {
        //         // Trigger the button action if validation passes
        //         const url = this.getAttribute('data-url');
        //         if (url) {
        //             window.location.href = url;
        //         }
        //     }
        // });

        document.getElementById("startquotation").addEventListener("click", function(e) {
            e.preventDefault();


            let bookingId = document.getElementById("booking_id").value;


            if (bookingId) {

                window.location.href = "{{ url('booking') }}/" + bookingId + "/edit";
            } else {

                alert('Create a booking first!');
            }
        });
        document.getElementById("startinspection").addEventListener("click", function(e) {
            e.preventDefault();


            let bookingId = document.getElementById("booking_id").value;


            if (!bookingId) {
                alert('Create a booking first!');

               
            }
            else if (!template){
                alert('Select A Template first!');
                document.getElementById("booking_id").value='';
            }
            else {

                window.location.href = "/inspectionbooking/"+ bookingId;
            }
        });
    </script>

    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO1Dw9T3wDRjN2RyrGLE2XTG86x46cIUc&loading=async&libraries=places">
    </script>
    <script>
        $(document).ready(function() {

            function getBookingId() {
                return $("#booking_id").val();
            }

            // Load comments based on the booking_id
            function loadComments() {
                let bookingId = getBookingId(); // Dynamically fetch the booking_id
                $.ajax({
                    url: "{{ route('journal.fetch') }}",
                    type: "GET",
                    data: {
                        booking_id: bookingId
                    },
                    dataType: "json",
                    success: function(data) {
                        let journalHtml = '';
                        $.each(data, function(key, journal) {
                            journalHtml += `
                            <div class="d-flex align-items-start mb-3">
                                <div>
                                    <small class="text-muted d-block">${journal.user.full_name} - ${new Date(journal.created_at).toLocaleString()}</small>
                                    <p class="mb-0">${journal.comment}</p>
                                </div>
                            </div>
                        `;
                        });
                        $("#journal-list").html(journalHtml);
                    },
                    error: function(xhr) {
                        $("#error-message").text(xhr.responseJSON.error);
                    }
                });
            }

            // Trigger the loadComments function on page load
            loadComments();

            // Click handler for sending a new comment
            $("#send-comment").click(function() {
                let comment = $("#comment-input").val();
                let bookingId = getBookingId(); // Fetch the booking_id dynamically

                if (!bookingId) {
                    alert("Booking ID is required to submit a comment.");
                    return;
                }

                if (comment.trim() !== "") {
                    $.ajax({
                        url: "{{ route('journal.store') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            booking_id: bookingId,
                            comment: comment
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#comment-input").val(""); // Clear input
                                loadComments(); // Reload comments
                                $("#comment-count").text(response
                                    .count); // Update count dynamically
                            }
                        },
                        error: function(xhr) {
                            $("#error-message").text(xhr.responseJSON.error);
                        }
                    });
                }
            });

        });
    </script>
    <script>
        window.onload = function() {
            // Check if the session is already cleared by checking the localStorage or sessionStorage
            if (!sessionStorage.getItem('customer_id')) {
                // Send AJAX request to clear session
                fetch('/clear-session')
                    .then(response => response.json())
                    .then(data => {

                        if (data.status === 'success') {
                            sessionStorage.setItem('sessionCleared', 'true');
                        }
                    })
                    .catch(error => console.error('Error clearing session:', error));
            }
        }
    </script>

    @push('script-page')
    @endpush
@endsection






@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <script>
        $(document).on("click", ".customModal", function() {
                const modalEl = document.getElementById('modaldemo1');
                modalEl.style.display = 'none';
                document.body.classList.remove('modal-open');
                document.querySelector('.modal-backdrop')?.remove();
        
        });
    </script>
@endsection
