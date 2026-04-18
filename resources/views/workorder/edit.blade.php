@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/accordion/accordion.css') }}" rel="stylesheet" />
@section('page-header')
    <style>
        .custom-modal .modal-dialog {
            max-width: 90%;
            /* Adjust to your preference */
        }

        .custom-modal .modal-content {
            width: 100%;
        }

        .pac-container {
            z-index: 10000 !important;
        }

        #map {
            height: 300px;
            width: 100%;
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

    <style>
        .form-check-label {
            font-size: 14px;
            color: #333;
            padding-left: 10px;
        }

        .pac-container {
            z-index: 10000 !important;
        }

        #map {
            height: 300px;
            width: 100%;
        }
    </style>

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
                    <a class="btn btn-primary ml-20 " href="{{ route('workorder.create') }}" data-size="lg"> <i
                            class="ti-plus"></i>
                        {{ __('Create WorkOrder') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
@endsection
@section('content')
    @include('messages_alert')
    <div class="row">
        <!-- Customer Details -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0 customer-header">Workorder Details</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="reference">WorkOrder Date</label>
                            <input type="text" class="form-control" id="reference" value="{{ $workOrder->created_date }}"
                                placeholder="Enter reference number" readonly>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="reference">WorkOrder Number</label>
                            <input type="text" class="form-control" id="workorder_id" value="{{ $workOrder->id }}"
                                placeholder="Enter reference number" readonly>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" readonly>
                                <option value="{{ $workOrder->status }}">{{ $workOrder->status }}</option>

                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="dueDate">Job Type</label>
                                                    <input type="text" class="form-control" id="Source"
                                                        value="{{ count($bookings)>0 ? $bookings[0]->job_type : '' }}" 
                                                         readonly>
                                                </div>
                      
                        @if ($appointment)
                            @if ($appointment->from_date)
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label for="status">Appointment Date</label>
                                    <input class="form-control" readonly value="{{ $appointment->from_date ?? '' }}">
                                </div>
                            @endif
                            @if ($appointment->from_time && $appointment->to_time)
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label for="status">Appointment Time</label>
                                    <input class="form-control" readonly
                                        value="{{ \Carbon\Carbon::create($appointment->from_time)->format('h:i:s A') ?? '' }}-{{ \Carbon\Carbon::create($appointment->to_time)->format('h:i:s A') ?? '' }}">
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0 customer-header">Customer Details</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        @foreach ($clients as $kay => $clientDetails)
                            <div class="col-xl-12 d-flex align-items-center mb-3">
                                <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Name') }}:</label>
                                <input type="text" class="form-control rounded-0"
                                    value="{{ $clientDetails->first_name }} {{ $clientDetails->last_name }}" readonly>
                            </div>
                            <input type="hidden" id="selectedCustomerId" value="{{ $clientDetails->id }}"
                                name="customer_id" />
                            <div class="col-xl-12 d-flex align-items-center mb-3">
                                <label class="form-label me-3 text-end"
                                    style="width: 30%;">{{ __('Client Type') }}:</label>
                                <input type="text" class="form-control rounded-0"
                                    value="{{ $clientDetails->client_type }}" readonly>
                            </div>
                            <div class="col-xl-12 d-flex align-items-center mb-3">
                                <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Email') }}:</label>
                                <input type="email" class="form-control rounded-0" value="{{ $clientDetails->email }}"
                                    readonly>
                            </div>
                            <div class="col-xl-12 d-flex align-items-center mb-3">
                                <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Phone') }}:</label>
                                <input type="text" class="form-control rounded-0" {{-- value="{{ $clientDetails->ccm }}" readonly> --}}
                                    value="+{{ preg_replace('/[^0-9]/', '', $clientDetails->ccm) . $clientDetails->phone_number }}"
                                    readonly>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @php
            $readonly = '';
        @endphp

        <!-- Vehicles Details -->
        @include('workorder.vehiclesDetailsEdit')

        <div class="col-md-2 d-none">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-light border-bottom">
                    <h6 class="card-title fw-bold mb-0">Chat</h6>
                </div>
                <div class="card-body p-3" style="max-height: 300px; overflow-y: auto;" id="journal-list">
                    <!-- Comments will be loaded here via AJAX -->
                </div>
                <div class="card-footer bg-light border-top p-2">
                    <div class="input-group">
                        <!-- Dynamically set this -->
                        <input type="text" id="comment-input" class="form-control form-control-sm"
                            placeholder="New comment">
                        <button class="btn btn-primary btn-sm" id="send-comment">Send</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Other Details</h3>
                </div>
                <div class="card-body pt-0">
                    <div id="accordion" class="w-100 br-2 overflow-hidden">
                        <div class="">
                            <div class="accor  bg-primary" id="headingThree3">
                                <h4 class="m-0">
                                    <a href="#collapseThree1" class="collapsed" data-toggle="collapse"
                                        aria-expanded="false" aria-controls="collapseThree">
                                        <i class="si si-cursor-move mr-2"></i>Booking Details
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree1" class="collapse b-b0" aria-labelledby="headingThree"
                                data-parent="#accordion">
                                <div class="border p-3">
                                    <div class="row">
                                        @if (count($bookings) > 0)
                                            @foreach ($bookings as $booking)
                                                <input type="hidden" id="bookingId" value="{{ $booking->id }}">
                                                <input type="hidden" id="cientId" value="{{ $booking->client }}">

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Booking ID</label>
                                                    <input type="text" class="form-control"
                                                        value="Booking-{{ $booking->id }}" readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="requestdate">Request Date</label>
                                                    <input type="date" class="form-control" id="requestdate"
                                                        value="{{ $booking->requested_date }}" required
                                                        {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="requesttime">Request Time</label>
                                                    <input type="time" class="form-control" id="requesttime"
                                                        value="{{ $booking->requested_time }}" required
                                                        {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col">
                                                    <label for="first_name" class="form-label">First name</label>
                                                    <input type="text" class="form-control mt-2" name="first_name"
                                                        id="first_name" value="{{ $booking->user->first_name }}"
                                                        {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col">
                                                    <label for="last_name" class="form-label">Last name</label>
                                                    <input type="text" class="form-control mt-2" name="last_name"
                                                        id="last_name" value="{{ $booking->user->last_name }}"
                                                        {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label" for="phone_number">Phone number</label>
                                                    <div class="input-group">
                                                        <!-- Country Code -->
                                                        <select id="m_cc" name="m_cc" class="form-control"
                                                            {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
                                                            <option value='+971'>UAE (+971)</option>
                                                            @foreach ($country_code as $id => $name)
                                                                <option value="{{ $id }}"
                                                                    {{ $id == $booking->user->ccm ? 'selected' : '' }}>
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" class="form-control" name="phone_number"
                                                            id="phone_number" value="{{ $booking->user->phone_number }}"
                                                            {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
                                                        <span class="text-danger" id="error_number"></span>
                                                    </div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Service Group</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $booking->service_group_names }}" readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Skill Group</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $booking->skill_group_names }}" readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Vehicle Registration No</label>
                                                    <input type="text" class="form-control"
                                                        value="Vehicle: {{ $booking->vehicless?->name }} ({{ $booking?->vehicless?->rego }})"
                                                        readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Status</label>
                                                     <input type="text" class="form-control"
                                                        value="{{ $booking->status }}" readonly>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="service_location" class="form-label">Service
                                                        Location</label>
                                                    <input type="text" id="service_location" name="service_location"
                                                        value="{{ $booking->service_location }}"
                                                        class="form-control mt-2" placeholder="Service Address" readonly
                                                        style="background-color: #e9ecef;">


                                                    <a class="float-end badge bg-primary text-light mt-1 {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'd-none' : '' }}"
                                                        href="#" data-bs-toggle="modal" id="mapmodal_click"
                                                        data-bs-target="#mapmodal">
                                                        {{ __('Select Location on Map') }}
                                                    </a>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="city" class="form-label">City</label>
                                                    <input type="text" class="form-control mt-2" name="city"
                                                        id="city" value="{{ $booking->city }}" readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="dueDate">LandMark</label>
                                                    <input type="text" class="form-control" id="landmark"
                                                        value="{{ $booking->landmark }}" required
                                                        {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
                                                </div>

												<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="dueDate">Source</label>
                                                    <input type="text" class="form-control" id="Source"
                                                        value="{{ $booking->source }}" required
                                                        {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }} readonly>
                                                </div>
                                                
                                                  <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                                      <label for="status">Supervisor</label>
                                                          <input class="form-control" readonly value="{{ $booking->supervisorinfo?->full_name }}">
                                                  </div>

												<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="dueDate">Description</label>
                                                    <input type="text" class="form-control" id="description"
                                                        value="{{ $booking->description }}">
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for=""></label>
                                                    <button class="btn btn-primary mt-4 btn-block"
                                                        id="bookingDetailsEdit">Update booking details</button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="accor  bg-primary" id="headingFour">
                                <h4 class="m-0">
                                    <a href="#collapseFour" class="collapsed" data-toggle="collapse"
                                        aria-expanded="false" aria-controls="collapseFour">
                                        <i class="si si-cursor-move mr-2"></i>Quotation Details
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="collapse b-b0" aria-labelledby="headingThree"
                                data-parent="#accordion">
                                <div class="border p-3">
                                    <table class="table mb-0 table-bordered border-top mb-0">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Send Date') }}</th>
                                                <th>{{ __('Product Name') }}</th>
                                                <th>{{ __('Quantity') }}</th>
                                                <th>{{ __('Unit Price') }}</th>
                                                <th>{{ __('UOM') }}</th>
                                                <th>{{ __('Discount Percentage') }}</th>
                                                <th>{{ __('Line Total') }}</th>
                                                <th>{{ __('Warranty') }}</th>
                                                <th>{{ __('Tax Percenatge') }}</th>
                                                <th>{{ __('Tax Amount') }}</th>
                                                <th>{{ __('Total Amount(Inc Total Amount)') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($quotations as $quotation)
                                                @foreach ($quotation as $item)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($item->send_date)->format('Y-m-d') }}
                                                        </td>
                                                        <td>{{ $item->product_name }}</td>
                                                        <td>{{ $item->qty }}</td>
                                                        <td>{{ $item->unit_price }}</td>
                                                        <td>{{ $item->uom }}</td>
                                                        <td>{{ $item->gstprice }}</td>
                                                        <td>{{ $item->linetotal }}</td>
                                                        <td>{{ $item->warrenty }}</td>
                                                        <td>{{ $item->taxpercentage }}</td>
                                                        <td>{{ $item->taxamount }}</td>
                                                        <td>{{ $item->totalamount }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="accor bg-primary" id="headingFive">
                                <h4 class="m-0">
                                    <a href="#collapseFive" class="collapsed" data-toggle="collapse"
                                        aria-expanded="false" aria-controls="collapseFive">
                                        <i class="si si-cursor-move mr-2"></i> Allocate Technicians
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFive" class="collapse b-b0" aria-labelledby="headingFive"
                                data-parent="#accordion">
                                <div class="border p-3">
                                    @if ($workOrder->allocation_status == 1 || $workOrder->allocation_status == 2)
                                        @if (count($technicians) > 0)
                                            <table class="table mb-0 table-bordered border-top mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('ID') }}</th>
                                                        <th>{{ __('Profile Picture') }}</th>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Phone Number') }}</th>
                                                        <th>{{ __('Scheduled Date') }}</th>
                                                        <th>{{ __('Scheduled Time') }}</th>
                                                        <th>{{ __('Distance (km)') }}</th>
                                                        <th>{{ __('Current Location') }}</th>
                                                        <th>{{ $workOrder->allocation_status == 1 ? __('Requested') : __('Accepted') }}
                                                        </th>
                                                        <th>{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($technicians as $technician)
                                                        <tr>
                                                            <td>{{ technicianPrefix() }}{{ $technician->technicians->technician_id ?? '' }}
                                                            </td>
                                                            <td>
                                                                <img src="{{ asset($technician->profile) }}"
                                                                    alt="{{ $technician->full_name }}'s picture"
                                                                    class="img-thumbnail"
                                                                    style="width: 100px; height: auto;">
                                                            </td>
                                                            <td>{{ $technician->full_name }}</td>
                                                            <td>{{ $technician->phone_number ?? '-' }}</td>

                                                            @php
                                                                $scheduledDateTime = explode(
                                                                    ' ',
                                                                    $workOrder->created_date ?? '- -',
                                                                );
                                                                $scheduledDate = $scheduledDateTime[0];
                                                                $scheduledTime = $scheduledDateTime[1] ?? '-';
                                                            @endphp

                                                            <td>{{ $appointment->from_date }}</td>
                                                            <td>{{ $appointment->from_time && $appointment->to_time ? $appointment->from_time . ' ' . $appointment->to_time : '' }}
                                                            </td>
                                                            <td>{{ isset($technician->distance) ? number_format($technician->distance, 2) . ' km' : '-' }}
                                                            </td>
                                                            <td>{{ $technician->current_location }}</td>
                                                            <td>{{ $technician->is_active == 1 ? 'Active' : 'Inactive' }}
                                                            </td>

                                                            <td>
                                                                <!-- Remove button -->
                                                                <form action="{{ route('workorder.removeTechnician') }}"
                                                                    method="POST" style="display: inline;">
                                                                    @csrf
                                                                    <input type="hidden" name="workorder_id"
                                                                        value="{{ $workOrder->id }}">
                                                                    <input type="hidden" name="technician_id"
                                                                        value="{{ $technician->id }}">
                                                                    <button type="submit"
                                                                        class="btn btn-danger btn-sm {{ in_array($workOrder->status, ['Completed', 'Invoiced', 'Paid']) ? 'd-none' : '' }}">Remove</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if ($workOrder->allocation_status == 1)
                                                <div class="form-group mt-3 d-none">
                                                    <a class="btn ripple btn-primary" data-target="#modaldemo1"
                                                        data-toggle="modal" href="#"
                                                        data-id="{{ $workOrder->id }}" id="viewTechnicians">
                                                        Allocate Technicians
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <p>No technicians allocated yet.</p>
                                        @endif
                                    @else
                                        <div class="form-group">
                                            <a class="btn ripple btn-primary" data-target="#modaldemo1"
                                                data-toggle="modal" href="#" data-id="{{ $workOrder->id }}"
                                                id="viewTechnicians">
                                                Allocate Technicians
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($inspection)
                                           <div class="">
                            <div class="accor bg-primary" id="headingnine">
                                <h4 class="m-0">
                                    <a href="#collapsenine" class="collapsed" data-toggle="collapse"
                                        aria-expanded="false" aria-controls="collapsenine">
                                        <i class="si si-cursor-move mr-2"></i> Inspection
                                    </a>
                                </h4>
                            </div>
                            <div id="collapsenine" class="collapse b-b0" aria-labelledby="headingnine"
                                data-parent="#accordion">
                                       <div class="card-body ">
                           <div class="row">
						<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
							<label for="requestdate">Inspection ID</label>
							<input type="text" class="form-control" name="" id="requestdate" value="INS00{{$inspection?->id}}"
								readonly>
						</div>
            
						<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
							<label for="requestdate">Created Date</label>
							<input type="date" class="form-control" name="" id="requestdate" value="{{\Carbon\Carbon::parse($inspection?->created_at)->format('Y-m-d')}}"
								readonly>
						</div>
                        	<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
							<label for="requestdate">Status</label>
							<input type="text" class="form-control" name="" id="requestdate" value="{{$inspection?->status}}"
								readonly>
						</div>
                        	<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
							<label for="requestdate">Template</label>
							<input type="text" class="form-control" name="" id="requestdate" value="{{$inspection?->template?->code}}"
								readonly>
						</div>
                         <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
							<label for="requestdate">Group Codes</label>
							<input type="text" class="form-control" name="" id="requestdate" value="{{implode(',',$groups)}}"
								readonly>
						</div>
                        	<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
							<label for="requestdate">Machine Hours</label>
							<input type="text" class="form-control" name="" id="requestdate" value="{{$inspection?->machine_hours}}"
								readonly>
						</div>
                       
                </div>

            </div>
                            </div>
                        </div>

            @endif
                        @include('workorder.imageSection')

                        @if (!empty($warrantyRegistrations))
                            <div class="">
                                <div class="accor bg-primary" id="headingSix">
                                    <h4 class="m-0">
                                        <a href="#collapseEight" class="collapsed" data-toggle="collapse"
                                            aria-expanded="false" aria-controls="collapseEight">
                                            <i class="si si-cursor-move mr-2"></i>Warranty Claim
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseEight" class="collapse b-b0" aria-labelledby="headingSeven"
                                    data-parent="#accordion">
                                    <div class="border p-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product ID</th>

                                                    <th>Product Name</th>

                                                    <th>Product Price</th>
                                                    <th>Invoice ID</th>
                                                    <th>Warranty Period</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>

                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($warrantyRegistrations as $key => $warrantyRegistration)
                                                    <tr>
                                                        <td>#REG000{{ $key + 1 }}</td>
                                                        <td>{{ $warrantyRegistration->product?->product_name }}</td>

                                                        <td>{{ $warrantyRegistration->product?->price }}</td>
                                                        <td>{{ $warrantyRegistration->workorder?->inv?->invoice_id }}</td>
                                                        <td>{{ $warrantyRegistration->warranty_period }} months</td>
                                                        <td>{{ $warrantyRegistration->warranty_start_date }}</td>
                                                        <td>{{ $warrantyRegistration->warranty_end_date }}</td>

                                                        <td>
                                                            @if ($warrantyRegistration->status == 3)
                                                                <span class="badge bg-success text-white">Already
                                                                    Claimed</span>
                                                            @elseif($warrantyRegistration->status == 1)
                                                                <span class="badge bg-warning text-dark">Active</span>
                                                            @elseif($warrantyRegistration->status == 4)
                                                                <span class="badge bg-info text-dark">Jump Start</span>
                                                            @endif
                                                        </td>

                                                        <td>

                                                            <div class="form-group">

                                                                @if ($warrantyRegistration->status != 3)
                                                                    <button type="button" data-toggle="modal"
                                                                        data-target="#warrantyclaimModal"
                                                                        class="btn ripple btn-primary text-white">Claim</button>
                                                                @endif
                                                                @if ($warrantyRegistration->status != 4)
                                                                    <button type="button" data-toggle="modal"
                                                                        data-target="#jumpstartModal"
                                                                        class="btn ripple btn-primary text-white">Jump
                                                                        Start</button>
                                                                @endif

                                                            </div>

                                                        </td>

                                                    </tr>

                                                    <div class="modal fade" id="warrantyclaimModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        Confirmation</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('warranty.claim', $warrantyRegistration->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" value="{{ $workOrder->id }}"
                                                                        name="workorderid" />
                                                                    <div class="modal-body">
                                                                        Are you sure you want to Claim this Product ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Confitm</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="jumpstartModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                        Confirmation</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('jump.start', $warrantyRegistration->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" value="{{ $workOrder->id }}"
                                                                        name="workorderid" />
                                                                    <div class="modal-body">
                                                                        Are you sure you want to jump start this Product ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Confitm</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="">
                            <div class="accor  bg-primary" id="headingSix">
                                <h4 class="m-0">
                                    <a href="#collapseSix" class="collapsed" data-toggle="collapse"
                                        aria-expanded="false" aria-controls="collapseSix">
                                        <i class="si si-cursor-move mr-2"></i>Customer Invoices
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseSix" class="collapse show b-b0" aria-labelledby="headingSix"
                                data-parent="#accordion">
                                <div class="border p-3">
                                    <!-- @if(count($Workorder_scraps)>0)
                              <div class="row">
                                <div class="col-12">
                                    <div class="card">
                             
                                        <div class="card-title p-2">Scrap Product Details</div>
                                     <table class="table ">
                                            <thead>

                                                <tr>
                                                    <th>{{ __('ID') }}</th>
                                                    <th>{{ __('Product ID') }}</th>
                                                    <th>{{ __('Product Name') }}</th>
                                                    <th>{{ __('Qty') }}</th>
                                                    <th>{{ __('Action') }}</th>

                                                    

                                                </tr>
                                            </thead>
                                            <tbody>
                                              

                                                @foreach ($Workorder_scraps as $key => $scrap)
                                                    <tr>
                                                        <td data-order="{{ $key + 1 }}">
                                                           {{ $key + 1 }}
                                                        </td>
                                                        <td>{{ $scrap->scrap_id }}</td>
                                                        
                                                        <td>{{ $scrap->product?->product_name }}</td>

                                                    
                                                  
                                                        <td>{{ $scrap->qty }}</td>
                                                        <td><button class="btn btn-danger btn-sm "><a class="text-white " href="{{route('workorderscrap.delete',$scrap->id)}}">Delete</a></button></td>

                                                    </tr>
                                                    
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                    </div>
                                    @endif    -->
                                    @if (count($invoices) > 0)
                                        <table class="table ">
                                            <thead>

                                                <tr>
                                                    <th>{{ __('ID') }}</th>
                                                    <th>{{ __('Invoice Date') }}</th>
                                                    <th>{{ __('Customer ID') }}</th>
                                                    <th>{{ __('Customer') }}</th>

                                                    <th>{{ __('Workorder ') }}</th>
                                                    <th>{{ __('Technician ') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Total') }}</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $statusColors = [
                                                        'invoiced' => 'badge-danger',
                                                        'paid' => 'badge-success',
                                                        'unpaid' => 'badge-secondary',
                                                    ];
                                                @endphp

                                                @foreach ($invoices as $key => $invoice)
                                                    <tr>
                                                        <td data-order="{{ $key + 1 }}">
                                                            {{ invoicePrefix() }}{{ $key + 1 }}
                                                        </td>
                                                        <td>{{ $invoice->invoice_date }}</td>
                                                        <td>CUS00{{ $key }}</td>
                                                        <td>{{ $invoice->clients->first_name }}</td>

                                                        <td>{{ workOrderPrefix() . $invoice->wo_id }}</td>
                                                        <td>{{ technicianPrefix() . $invoice->wo_id }}</td>
                                                        <td>
                                                            @php
                                                                $colorClass =
                                                                    $statusColors[strtolower($invoice->status)] ??
                                                                    'badge-dark';
                                                            @endphp
                                                            <span
                                                                class="badge {{ $colorClass }}">{{ ucfirst($invoice->status) }}</span>
                                                        </td>
                                                        <td>{{ $invoice->final_amount }}</td>

                                                    </tr>
                                                    @include('invoice.edit')
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header flex items-center justify-between">
                                                        <div class="card-title">Product Details</div>
                                                        <label class="custom-switch">
                                                            <input type="hidden" name="allproducts" id="allproducts"
                                                                value="0">
                                                            <input type="checkbox" name="is_active" class="toggle-status"
                                                                onchange="updateStatus(this)">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
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
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table text-nowrap table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Action</th>
                                                                        <th>Type</th>
                                                                        <th>Product ID</th>
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
                                                                        <th>Comment</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="productTableBody"></tbody>
                                                            </table>
                                                            <button type="button"
                                                                class="btn btn-secondary btn-wave waves-effect waves-light"
                                                                id="addProduct">Add Product</button>
                                                        </div>





                                                        <div style="float: right; width: 35%; margin-top: 20px;">
                                                            <!-- Collapsible Card -->
                                                            <div class="card custom-card collapse-card border shadow-lg">
                                                                <div
                                                                    class="card-header d-flex align-items-center justify-content-between bg-primary text-white">
                                                                    <div class="card-title mb-0 text-white">
                                                                        Order Summary
                                                                    </div>
                                                                    <a href="javascript:void(0);"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#orderSummaryCollapse"
                                                                        aria-expanded="true"
                                                                        aria-controls="orderSummaryCollapse"
                                                                        class="text-white">
                                                                        <i
                                                                            class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                                                                        <i
                                                                            class="ri-arrow-up-s-line collapse-close fs-18"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="collapse show" id="orderSummaryCollapse">
                                                                    <div class="card-body">
                                                                        <div class="d-flex justify-content-between">
                                                                            <span><strong>Subtotal Excl.
                                                                                    VAT:</strong></span>
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

                                                                        <div
                                                                            class="card-footer d-flex justify-content-between mt-3">
                                                                            <span><strong>Total Incl. VAT:</strong></span>
                                                                            <span id="totalInclVAT">0.00</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="btn-list mt-4">
                                                                <div class="d-flex justify-content-center gap-4">

                                                                    <div class="form-group">
                                                                        <button class="btn btn-primary" id="startBooking">
                                                                            Update Items
                                                                        </button>&nbsp;
                                                                        <a href="{{ url('bookinginvoice/' . $workOrder->id) }}"
                                                                            class="btn btn-primary">Invoice</a>
                                                                        <!-- @if(count($Workorder_scraps)==0)
                                                                        <a class="btn btn-primary  ml-20 customModal2"
                                                                            href="#" data-size="lg"
                                                                            data-url="{{ url('workorder/scrap/' . $workOrder->id . '/admin') }}"
                                                                            data-title="Scrap Datails">
                                                                            {{ __('Scrap ') }}
                                                                        </a>
                                                                        @endif -->
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="accor  bg-primary" id="headingFive">
                                <h4 class="m-0">
                                    <a href="#collapseSeven" class="collapsed" data-toggle="collapse"
                                        aria-expanded="false" aria-controls="collapseSeven">
                                        <i class="si si-cursor-move mr-2"></i>Customer Payment
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseSeven" class="collapse b-b0" aria-labelledby="headingSeven"
                                data-parent="#accordion">
                                <div class="border p-3">
                                    @if (count($payments) > 0)
                                        <table class="table mb-0 table-bordered border-top mb-0">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Payment ID') }}</th>
                                                    <th>{{ __('Payment Date') }}</th>
                                                    <th>{{ __('Customer ID') }}</th>
                                                    <th>{{ __('Customer Name') }}</th>
                                                    <th>{{ __('Invoice ID') }}</th>
                                                    <th>{{ __('Work Order ID') }}</th>
                                                    <th>{{ __('Total Amount') }}</th>
                                                    <th>{{ __('Attachment') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($payments as $item)
                                                    <tr>
                                                        <td>{{ $item->id }}
                                                        </td>
                                                        <td>{{ $item->payment_date }}
                                                        </td>
                                                        <td>000{{ $item->user->id }}
                                                        </td>
                                                        <td>{{ $item->user->fullname }}
                                                        </td>
                                                        <td>{{ $item->invoice }}</td>
                                                        <td>000{{ $workOrder->id }}</td>
                                                        <td>{{ array_sum(explode(',',$item->paid_amount)) }}</td>
                                                        <td><a
                                                                href="{{ route('download', $item->id) }}">{{ $item->image_path ? 'YES' : 'NO' }}</a>
                                                        </td>
                                                        <td>{{ $item->status == 'paid' ? 'PAID' : $item->status }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <h6 class='text-center'>There is no Payment</h6>

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-primary customModal"
                        data-url="{{ route('workorder.cancel', $workOrder->id) }}">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="mapmodal" tabindex="-1" aria-labelledby="mapmodalLabel" aria-hidden="true"
        style="background-color: #000000a8;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapmodalLabel">Please confirm location by dragging the marker on the map
                    </h5>
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

    <input type="hidden" id="city" name="city" />

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
        document.addEventListener("DOMContentLoaded", function() {
            const techniciansTbody = document.getElementById("technicians-tbody");

            function fetchTechnicians(checkinCheckout) {
                const workOrderId = @json($workOrder->id);
                const skillGroup = document.getElementById("skill_group").value;
                const url =
                    `/gettechnicianallocate/${workOrderId}?checkin_checkout=${checkinCheckout}&skill_group=${skillGroup}`;

                console.log("Fetching technicians with URL:", url);

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        techniciansTbody.innerHTML = html;
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
        $('#modaldemo1').on('shown.bs.modal', function() {
            $('#skill_group').select2({
                placeholder: "Select Skill Group",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('#default-technicians').show();
            $('#other-content').hide();


            $('#switch-lg').change(function() {
                if ($(this).is(':checked')) {

                    $('#default-technicians').hide();
                    $('#other-content').show();
                } else {

                    $('#default-technicians').show();
                    $('#other-content').hide();
                }
            });



        });
    </script>

    <script>
        let items = [];
              fetchAndShowFirstProduct();
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

        async function fetchQuotProduct() {

            const workOrderId = @json($workOrder->id);

            try {
                const response = await fetch(
                    `/getquotation/workorder?workorder=${workOrderId}`
                );
                const products = await response.json();
                console.log(products);
                return products.length > 0 ? products : null;
            } catch (error) {
                console.error('Error fetching the quote products:', error);
                return null;
            }
        }

        async function fetchFirstProduct(vehicleId) {
            const allproducts = document.getElementById('allproducts').value;
            try {
                const response = await fetch(
                    `/searchproducts?vehicle_id=${encodeURIComponent(vehicleId)}&allproducts=${encodeURIComponent(allproducts)}`
                );
                const products = await response.json();

                return products.length > 0 ? products[0] : null;
            } catch (error) {
                console.error('Error fetching the first product:', error);
                return null;
            }
        }

        async function fetchAndShowFirstProduct() {

            // if (items.length === 0) {
            const quotProducts = await fetchQuotProduct();

            if (quotProducts.length > 0) {

                for (let i = 0; i < quotProducts.length; i++) {
                    addProductToTable(quotProducts[i], 1);
                }
            }

        }

        // window.onload = function() {
            // console.log(quotProducts.length);
  


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

        function addProductToTable(productData, exist = 0) {
            const unitPrice = parseFloat(productData.price) || productData.unit_price || 0;
            const quantity = productData.qty;
            const discountPercentage = parseFloat(productData.discount_percentage) || parseFloat(productData.gstprice) || 0;

            const discount = (quantity * unitPrice) * (discountPercentage / 100);
            const lineTotal = (unitPrice * quantity) - discount || productData.linetotal;
            const id = productData.id || productData.product_id || null;

            const taxPercentage = parseFloat(productData.tax) || parseFloat(productData.taxpercentage) || 0;
            const taxAmount = lineTotal * (taxPercentage / 100);
            const totalAmountIncTax = lineTotal + taxAmount;

            const item = {
                id: id,
                productName: productData.product_name,
                item_no: productData.item_no || '',
                description: productData.type || productData.description || '',
                unitPrice,
                quantity,
                uom: productData.uom,
                comment: productData.comment || '',
                uom_name: productData.uom_name || productData.uom||'',
                lineTotal,
                item_type: productData.item_type || '',
                discount_percentage: discountPercentage,
                warranty: productData.warrenty || 'N/A',
                location: productData.warehouse_name || 'N/A',
                tax_percentage: taxPercentage,
                tax_amount: taxAmount.toFixed(3),
                total_amount_inc_tax: 0,
                exist: exist
            };
            

            items.push(item);
            renderTable();
            updateSummary();
        }

        function addProductToTable2(productData, exist = 0) {
            const unitPrice = parseFloat(productData.price) || productData.unit_price || 0;
            const quantity = productData.qty;
            const discountPercentage = parseFloat(productData.discount_percentage) || parseFloat(productData.gstprice) || 0;

            const discount = (quantity * unitPrice) * (discountPercentage / 100);
            const lineTotal = (unitPrice * quantity) - discount || productData.linetotal;
            const id = productData.id || productData.product_id || null;

            const taxPercentage = parseFloat(productData.tax) || parseFloat(productData.taxpercentage) || 0;
            const taxAmount = lineTotal * (taxPercentage / 100);
            const totalAmountIncTax = lineTotal + taxAmount;

            const item = {
                id: id,
                productName: productData.product_name,
                description: productData.type || productData.description || '',
                unitPrice,
                quantity,
                uom: productData.uom,
                comment: productData.comment || '',
                uom_name: productData.uom_name || '',
                lineTotal,
                location: productData.warehouse_name,
                item_type: productData.item_type || '',
                discount_percentage: discountPercentage,
                warranty: productData.warrenty || 'N/A',
                warehouse_name: productData.warehouse_name || 'N/A',
                tax_percentage: taxPercentage,
                tax_amount: taxAmount.toFixed(3),
                total_amount_inc_tax: totalAmountIncTax.toFixed(3),
                exist: exist
            };

            items.push(item);
            renderTable2();
            updateSummary();
        }

        function renderTable() {
            const tableBody = document.getElementById('productTableBody');
            tableBody.innerHTML = '';

			console.log(items);

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
            <td class="item_no-cell" data-index="${index}">${item.item_no}</td>
            <td class="product-name-cell" data-index="${index}">${item.productName}</td>

            <td class="unit-price-cell" data-index="${index}">${item.unitPrice}</td>
            <td class="qty-cell" data-index="${index}">${item.quantity}</td>
               <td class="qty-cell" data-index="${index}">${item.uom_name}</td>
            <td class="discount-percentage-cell" data-index="${index}">${item.discount_percentage ? `${item.discount_percentage}%` : 'N/A'}</td>
            <td class="line-total-cell" data-index="${index}">${item.lineTotal}</td>
            <td class="warranty-cell" data-index="${index}">${item.warranty}</td>
         
            <td>
                <input type="number" class="tax-percentage-cell" data-index="${index}" value="${item.tax_percentage}" min="0" max="100">
            </td>
            <td class="tax-amount-cell" data-index="${index}">${item.tax_amount}</td>
            <td class="total-amount-cell" data-index="${index}">${item.total_amount_inc_tax}</td>
            <td class="comment-cell" data-index="${index}">${item.comment}</td>
            <input type="hidden" class="product-id" value="${item.id}" />
             <input type="hidden" class="product-exist" value="${item.exist}" />
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
                   if(newItemType=='noninventory')
                    {
                        console.log(newItemType);
                        items[index].product_id = '';
                        items[index].id = '';
                        items[index].productName = '';
                        items[index].item_type = 'noninventory';
                        items[index].item_no = '';
                        items[index].unitPrice =  0;
                        items[index].discount_percentage = 0;
                        items[index].warranty =  'N/A';
                        items[index].quantity =  0;
                        items[index].item_type = newItemType;
                        items[index].discount_percentage = 0;
                        items[index].lineTotal = 0;
                        items[index].tax_percentage= 0;
                        items[index].tax_amount= 0;
                        items[index].uom = '';
                        items[index].uom_name = '';
                        items[index].total_amount_inc_tax =0;
                        items[index].comment='';
                        
                   
                    }
                    else{
                    // Fetch the first product of the selected item type
                    const firstProduct = await fetchFirstProductByType(newItemType);

                    if (firstProduct) {
                        items[index].id = firstProduct.id;
                        items[index].productName = firstProduct.product_name;
                        items[index].description = firstProduct.type || '';
                        items[index].item_no = firstProduct.item_no || '';
                        items[index].warranty = firstProduct.warranty || 'N/A';
                        items[index].uom_name= firstProduct.uom_name || '';
                        items[index].uom = firstProduct.uom|| '';
                        items[index].location = firstProduct.warehouse_name;
                        items[index].unitPrice = parseFloat(firstProduct.price) || 0;
                        items[index].discount_percentage = parseFloat(firstProduct.discount_percentage) || 0;

                        const discount = (items[index].quantity * items[index].unitPrice) * (items[index].discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * items[index].quantity) - discount;
                        items[index].tax_percentage= parseFloat(firstProduct.tax) || 0;
                        items[index].tax_amount= items[index].lineTotal * (items[index].tax_percentage / 100);
                        
                        items[index].total_amount_inc_tax =items[index].lineTotal + items[index].tax_amount;
                        items[index].item_type = newItemType;
                       
                       
                    }
                }
                     renderTable();
                    updateSummary();
                
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
                    const currentItemNo = items[index].item_no;
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentProductName;
                    input.classList.add('product-input');
                    if(currentItemType=='noninventory')
                    {
                    

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newproductname = this.value.trim();
                        items[index].productName = newproductname;
                        
                        renderTable();
                    });
                    }
                    else{

                    const dropdown = document.createElement('div');
                    dropdown.classList.add('dropdown-suggestions');
                    dropdown.style.position = 'absolute';
                    dropdown.style.backgroundColor = '#fff';
                    dropdown.style.border = '1px solid #ccc';
                    dropdown.style.boxShadow = '0px 4px 6px rgba(0, 0, 0, 0.1)';
                    dropdown.style.zIndex = '1000';
                    dropdown.style.maxHeight = '200px';
                    dropdown.style.overflowY = 'auto';
                    dropdown.style.width = '30%';
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
                        header.innerHTML = `<div style="width: 30%; padding-right: 10px;">PRODUCT ID</div>
                                <div style="width: 70%;">PRODUCT NAME</div>`;
                        dropdown.appendChild(header);

                        similarProducts.forEach((product, i) => {
                            const option = document.createElement('div');
                            option.classList.add('dropdown-option');
                            option.style.display = 'flex';
                            option.style.padding = '8px';
                            option.style.cursor = 'pointer';
                            option.style.borderBottom = '1px solid #eee';
                            option.innerHTML = `<div style="width: 30%; padding-right: 10px;">${product.item_no}</div>
                                    <div style="width: 70%;">${product.product_name}</div>`;

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
                        items[index].uom = product.uom;
                         items[index].uom_name = product.uom_name;
                        items[index].location = product.warehouse_name;
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

            document.querySelectorAll('.item_no-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentDescription = items[index].item_no;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentDescription;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newDescription = this.value.trim();
                        items[index].item_no = newDescription;
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
                            discount;

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
     document.querySelectorAll('.comment-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentcomment = items[index].comment;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentcomment;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newcomment = this.value.trim();
                        items[index].comment = newcomment;
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function collectFormData() {
                let formData = {
                    workorder_id: $('#workorder_id').val(),
                    products: collectProductDetails(),

                };

                return formData;
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
                        product_exist: $(this).find('.product-exist')
                            .val(), // Retrieve the hidden product exist
                        item_no: $(this).find('td:eq(2)').text(),
                        item_type: $(this).find('#item_type').val(),
                        product_name: $(this).find('td:eq(3)').text(),

                        unit_price: $(this).find('td:eq(4)').text(),
                        quantity: $(this).find('td:eq(5)').text(),
                        uom_name: $(this).find('td:eq(6)').text(),
                        gst: $(this).find('td:eq(7)').text(),
                        line_total: $(this).find('td:eq(8)').text(),
                        warranty: $(this).find('td:eq(9)').text(),
                        location: $(this).find('td:eq(10)').text(),
                        taxpercentage: $(this).find('.tax-percentage-cell').val(),
                        taxamount: $(this).find('.tax-amount-cell').text(),
                        totalamount: $(this).find('.total-amount-cell').text(),
                        comment: $(this).find('.comment-cell').text(),

                    };
                    products.push(product);
                });

                return products;
            }

            $('#startBooking').click(function(e) {
                e.preventDefault();

                let formData = collectFormData();




                $.ajax({
                    url: "{{ route('quotation.update') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Quotation updated successfully');

                    },
                    error: function(error) {
                        alert('Error starting booking');
                        console.error(error);
                    }
                });
            });
        });
    </script>

    <script>
        function toggleFaq(index) {
            const faqAnswer = document.getElementById(`faq${index}`);
            const faqQuestion = faqAnswer.previousElementSibling;
            faqAnswer.classList.toggle('show');
            faqQuestion.classList.toggle('active');
        }

        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const bookingModal = document.getElementById("bookingModal");
            const closeBookingModalButton = document.getElementById(
                "closeBookingModal");


            $(document).on('click', '.book-button', function() {
                const technicianName = $(this).closest('tr').find(
                    'td:first').text();
                const technicianId = $(this).data('technician-id');


                $('#technicianName').val(technicianName);
                $('#technicianId').val(technicianId);

                bookingModal.style.display = "block";
            });

            closeBookingModalButton.addEventListener("click", function() {
                bookingModal.style.display = "none";
            });

            window.addEventListener("click", function(event) {
                if (event.target === bookingModal) {
                    bookingModal.style.display = "none";
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#customer_id').select2({
                placeholder: 'Select your customer',
                allowClear: true
            });
            $('#searchPayment').select2({
                placeholder: 'Select your payment',
                allowClear: true
            });
            $('#searchInvoice').select2({
                placeholder: 'Select your invoice',
                allowClear: true
            });
            $('#searchVehicle').select2({
                placeholder: 'Select your Equipment',
                allowClear: true
            });
            $('#searchTechnician').select2({
                placeholder: 'Select your technician',
                allowClear: true
            });
            $('#searchInspection').select2({
                placeholder: 'Select your inspection',
                allowClear: true
            });
            $('#searchBooking').select2({
                placeholder: 'Select your booking',
                allowClear: true
            });

            var selectedCustomerId = $('#customer_id').val();
            if (selectedCustomerId) {
                fetchClientData(selectedCustomerId);
            }
            $('#customer_id').on('change', function() {
                var customerId = this.value;
                fetchClientData(customerId);
            });

            function fetchClientData(customerId) {
                if (customerId) {
                    fetch(`/workorder-client/fetch/api/${customerId}`)
                        .then(response => response.json())
                        .then(data => {
                            $('#customer_name').val(data.full_name);
                            $('#phone').val(data.phone);
                            $('#email').val(data.email);
                            $('#type').val(data.type);
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    $('#customer_name').val('');
                    $('#phone').val('');
                    $('#email').val('');
                    $('#type').val('');
                }
            }

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
                            var suggestions = $(
                                '#addressSuggestions');
                            suggestions.empty();
                            if (data.candidates && data
                                .candidates.length > 0) {
                                suggestions.show();
                                data.candidates.forEach(
                                    function(place) {
                                        var listItem =
                                            $(
                                                '<li class="list-group-item"></li>')
                                            .text(place
                                                .formatted_address
                                            )
                                            .data(
                                                'address',
                                                place
                                                .formatted_address
                                            )
                                            .on('click',
                                                function() {
                                                    $('#service_location')
                                                        .val(
                                                            $(
                                                                this)
                                                            .data(
                                                                'address'
                                                            )
                                                        );
                                                    suggestions
                                                        .hide();
                                                });
                                        suggestions
                                            .append(
                                                listItem
                                            );
                                    });
                            } else {
                                suggestions.hide();
                            }
                        },
                        error: function() {
                            alert(
                                "Error fetching address.");
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
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("availabilityModal");
            const openModalButton = document.getElementById("openModalButton");
            const closeModalButton = document.getElementById(
                "closeModalButton");

            // Show modal on button click
            openModalButton.addEventListener("click", function() {
                modal.style.display = "block";
                // Load initial data
                updateTechnicianAvailability($('#currentDate').val());
            });

            // Close modal on button click
            closeModalButton.addEventListener("click", function() {
                modal.style.display = "none";
            });

            // Close modal when clicking outside of it
            window.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });




            // Update the day of the week based on the selected date
            function updateDayOfWeek(date) {
                const dayOfWeek = new Date(date).toLocaleString('default', {
                    weekday: 'long'
                });
                $('#currentDay').text(dayOfWeek);
            }

            // Event listener for previous date button
            $('#prevDateBtn').on('click', function() {
                let currentDate = new Date($('#currentDate').val());
                currentDate.setDate(currentDate.getDate() - 1);
                $('#currentDate').val(currentDate.toISOString().split(
                    'T')[0]); // Set the new date value
                updateTechnicianAvailability($('#currentDate')
                    .val()); // Update availability
            });

            // Event listener for next date button
            $('#nextDateBtn').on('click', function() {
                let currentDate = new Date($('#currentDate').val());
                currentDate.setDate(currentDate.getDate() + 1);
                $('#currentDate').val(currentDate.toISOString().split(
                    'T')[0]); // Set the new date value
                updateTechnicianAvailability($('#currentDate')
                    .val()); // Update availability
            });

            // Initialize time bars based on availability
            function initializeTimeBars() {
                const timeBars = document.querySelectorAll(
                    '.time-bar-manipulatable');
                timeBars.forEach(bar => {
                    const startTime = bar.getAttribute('data-start');
                    const endTime = bar.getAttribute('data-end');

                    const startHour = parseInt(startTime.split(':')[0],
                        10);
                    const startMinute = parseInt(startTime.split(':')[
                        1], 10);
                    const endHour = parseInt(endTime.split(':')[0], 10);
                    const endMinute = parseInt(endTime.split(':')[1],
                        10);

                    const startInMinutes = (startHour * 60) +
                        startMinute;
                    const endInMinutes = (endHour * 60) + endMinute;

                    const totalWorkingMinutes = endInMinutes -
                        startInMinutes;
                    const totalHoursRange = (endHour - startHour) * 60;
                    const barWidth = (totalWorkingMinutes /
                        totalHoursRange) * 100;

                    const startPercentage = ((startInMinutes -
                        startHour * 60) / totalHoursRange) * 100;
                    bar.style.left = `${startPercentage}%`;
                    bar.style.width = `${barWidth}%`;
                });
            }

            // Initialize time bars on first load
            initializeTimeBars();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fromDateInput = document.getElementById("fromDate");
            const toDateInput = document.getElementById("toDate");
            const fromTimeInput = document.getElementById("fromTime");
            const toTimeInput = document.getElementById("toTime");

            const today = new Date().toISOString().split("T")[0];
            fromDateInput.value = today;
            toDateInput.value = today;

            function setTimeFields() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                fromTimeInput.value = `${hours}:${minutes}`;

                const toTime = new Date(now.getTime() + 30 * 60000);
                const toHours = String(toTime.getHours()).padStart(2, '0');
                const toMinutes = String(toTime.getMinutes()).padStart(2, '0');
                toTimeInput.value = `${toHours}:${toMinutes}`;
            }

            setTimeFields();

            fromDateInput.addEventListener("change", function() {
                toDateInput.min = this.value;
                if (toDateInput.value < this.value) {
                    toDateInput.value = this.value;
                }
            });

            toDateInput.addEventListener("change", function() {
                if (this.value < fromDateInput.value) {
                    this.value = fromDateInput.value;
                }
            });

            fromTimeInput.addEventListener("change", function() {
                const fromTime = new Date(
                    `1970-01-01T${this.value}:00`);
                const minToTime = new Date(fromTime.getTime() + 30 *
                    60000);
                const minToTimeStr = minToTime.toISOString().substr(11,
                    5);
                toTimeInput.min = minToTimeStr;

                if (toTimeInput.value < minToTimeStr) {
                    toTimeInput.value = minToTimeStr;
                }
            });

            toTimeInput.addEventListener("change", function() {
                const fromTime = new Date(
                    `1970-01-01T${fromTimeInput.value}:00`);
                const minToTime = new Date(fromTime.getTime() + 30 *
                    60000);
                const minToTimeStr = minToTime.toISOString().substr(11,
                    5);
                if (this.value < minToTimeStr) {
                    this.value = minToTimeStr;
                }
            });
        });
    </script>

    <script>
        $(document).on("click", ".customModal", function() {

            $(".modaldemo1").modal("hide");


            setTimeout(() => {
                $("#yourModalId").modal("show");
            }, 500);
        });

        $(document).on("shown.bs.modal", function() {
            const today = new Date();

            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = (date.getMonth() + 1).toString().padStart(2, "0");
                const day = date.getDate().toString().padStart(2, "0");
                return `${year}-${month}-${day}`;
            };

            $("#fromDate").val(formatDate(today));

            const now = new Date();

            $("#fromTime").val(now.toTimeString().substr(0, 5));

            function updateToTime() {
                let fromTime = $("#fromTime").val();
                if (fromTime) {
                    let [hours, minutes] = fromTime.split(":").map(Number);
                    minutes += 30;
                    if (minutes >= 60) {
                        hours += 1;
                        minutes -= 60;
                    }
                    const toTime = `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
                    $("#toTime").val(toTime);
                }
            }

            // When the fromTime changes, update toTime
            $("#fromTime").on("input", updateToTime);

            // Call the function initially to set the toTime
            updateToTime();
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to get the current booking_id value
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
                if (comment.trim() !== "") {
                    let bookingId = getBookingId(); // Dynamically fetch the booking_id
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
                                $("#comment-input").val(""); // Clear the input field
                                loadComments
                                    (); // Reload the comments after a successful submission
                            }
                        },
                        error: function(xhr) {

                        }
                    });
                }
            });
        });
    </script>
    @push('script-page')
    @endpush
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/accordion.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--- Internal Accordion Js -->
    <script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/accordion.js') }}"></script>
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @include('workorder.workOrderMap')
@endsection