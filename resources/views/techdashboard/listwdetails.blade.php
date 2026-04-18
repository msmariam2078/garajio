@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/accordion/accordion.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
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
        .pac-container {
            z-index: 10000 !important;
        }

        #map {
            height: 300px;
            width: 100%;
        }

        .heading-scroll,
        .collapse {
            overflow-x: auto;
            white-space: nowrap;
        }

        /* Optional: makes link block wider so it overflows */
        .min-width-content {
            min-width: 400px;
            /* Adjust this as needed */
        }

        /* Only apply scroll on mobile */
        @media (max-width: 768px) {
            .heading-scroll {
                display: block;
            }
        }
    </style>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">List WorkOrder Details</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <div class="row">
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
                            @php
                                $bookingData = json_decode($workOrder->booking, true);
                            @endphp

                            <input type="hidden" class="form-control" id="booking_id" value="{{ $bookingData[0] ?? '' }}"
                                readonly>
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
                          <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="status">Job Type</label>
                             <input class="form-control" readonly value="{{ $bookings[0]->job_type ?? '' }}">
                            <!-- <select class="form-control" name="status" readonly> -->
                                <!-- <option value="">Pre-delivery Inspection</option>
                                 <option value="">Site Inspection</option>

                            </select> -->
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

        <!-- Customer Details -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0 customer-header">Customer Details</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">

                        <div class="col-xl-12 d-flex align-items-center mb-3">
                            <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Name') }}:</label>
                            <input type="text" class="form-control rounded-0"
                                value="{{ $workOrder->client->first_name }} {{ $workOrder->client->last_name }}" readonly>
                            <input type="hidden" value="{{$workOrder->customer_id}}" id="customer_id"/>
                             </div>
                        <div class="col-xl-12 d-flex align-items-center mb-3">
                            <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Client Type') }}:</label>
                            <input type="text" class="form-control rounded-0"
                                value="{{ $workOrder->client->client_type }}" readonly>
                        </div>
                        <div class="col-xl-12 d-flex align-items-center mb-3">
                            <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Email') }}:</label>
                            <input type="email" class="form-control rounded-0" value="{{ $workOrder->client->email }}"
                                readonly>
                        </div>
                        <div class="col-xl-12 d-flex align-items-center mb-3">
                            <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Phone') }}:</label>
                            <input type="text" class="form-control rounded-0"
                                value="+{{ preg_replace('/[^0-9]/', '', $workOrder->client->ccm) . $workOrder->client->phone_number }}"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $readonly = $readonly = in_array($workOrder->status, ['Open', 'Pending']) ? 'readonly' : '';
        @endphp

        <!-- Vehicles Details -->
        @include('workorder.vehiclesDetailsEdit')

        <div class="col-md-2 d-none">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-light border-bottom">
                    <h6 class="card-title fw-bold mb-0">Comments</h6>
                </div>
                <div class="card-body p-3" style="max-height: 250px!important; overflow-y: auto;" id="journal-list">

                </div>
                <div class="card-footer bg-light border-top p-2">
                    <div class="input-group">

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
                    <div id="accordion" class="w-100 br-2 overflow-hidden scroll-x">
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
                                <div class="border px-2 pt-1">
                                    <div class="row">
                                        @if (count($bookings) > 0)
                                            @foreach ($bookings as $booking)
                                              

                                         

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="requestdate">Request Date</label>
                                                    <input type="date" class="form-control" id="requestdate"
                                                        value="{{ $booking->requested_date }}" required readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="requesttime">Request Time</label>
                                                    <input type="time" class="form-control" id="requesttime"
                                                        value="{{ $booking->requested_time }}" required readonly>
                                                </div>

                                                <div class="col">
                                                    <label for="first_name" class="form-label">First name</label>
                                                    <input type="text" class="form-control mt-2" name="first_name"
                                                        id="first_name" value="{{ $booking->user->first_name }}"
                                                        readonly>
                                                </div>

                                                <div class="col">
                                                    <label for="last_name" class="form-label">Last name</label>
                                                    <input type="text" class="form-control mt-2" name="last_name"
                                                        id="last_name" value="{{ $booking->user->last_name }}" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label" for="phone_number">Phone number</label>
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <select id="m_cc" name="m_cc" class="form-control"
                                                                readonly>
                                                                <option value='+971'>UAE (+971)</option>
                                                                @foreach ($country_code as $id => $name)
                                                                    <option value="{{ $id }}"
                                                                        {{ $id == $booking->user->ccp ? 'selected' : '' }}>
                                                                        {{ $name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Phone Number -->
                                                        <div class="col-7 pl-0">
                                                            <input type="text" class="form-control"
                                                                name="phone_number" id="phone_number"
                                                                value="{{ $booking->user->phone_number }}" readonly>
                                                            <span class="text-danger" id="error_number"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Skill Group</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $booking->skill_group_names }}" readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Equipment Registration No</label>
                                                    <input type="text" class="form-control"
                                                        value="Vehicle: {{ $booking->vehicless?->name }} ({{ $booking->vehicless?->rego }})"
                                                        readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Status</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $booking->status }}" readonly>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label>Supervisor</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $booking->supervisorinfo?->full_name}}" readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="service_location" class="form-label">Service
                                                        Location</label>
                                                    <input type="text" id="service_location" name="service_location"
                                                        value="{{ $booking->service_location }}"
                                                        class="form-control mt-2" placeholder="Service Address" readonly
                                                        style="background-color: #e9ecef;">
                                                    <a class="float-end badge bg-primary text-light mt-1 d-none {{ $readonly == 'readonly' ? 'd-none' : ' ' }}"
                                                        href="#" data-bs-toggle="modal" id="mapmodal_click"
                                                        data-bs-target="#mapmodal">
                                                        {{ __('Select Location on Map') }}
                                                    </a>

                                                    <a class="btn btn-dark mt-3" target="_blank"
                                                        href="https://www.google.com/maps/dir/?api=1&destination={{ $workOrder->service_lat }},{{ $workOrder->service_lng }}">
                                                        <i class="fas fa-share pr-2"></i>
                                                        See client location
                                                        <i class="fas fa-map-marker-alt pl-2"></i>
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
                                                        value="{{ $booking->landmark }}" required {{ $readonly }}
                                                        {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="dueDate">Source</label>
                                                    <input type="text" class="form-control" id="Source"
                                                        value="{{ $booking->source }}" required
                                                        {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}
                                                        readonly>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for="dueDate">Description</label>
                                                    <input type="text" class="form-control" id="description"
                                                        value="{{ $booking->description }}">
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mb-3">
                                                    <label for=""></label>
                                                    <button class="btn btn-primary mt-4 btn-block"
                                                        id="bookingDetailsEdit">Edit booking details</button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                  @if($workOrder->allocation_status ==2)
                   <div class="">
                            <div class="accor  bg-primary" id="headingThro">
                                <h4 class="m-0">
                                    <a href="#collapseThro" class="collapsed" data-toggle="collapse"
                                        aria-expanded="false" aria-controls="collapseThree">
                                        <i class="si si-cursor-move mr-2"></i>Inspection
                                    </a>
                                </h4>
                            </div>
                           <div id="collapseThro" class="collapse {{$workOrder->status=='Confirmed' || $workOrder->status=='Start Inspection' ?'show' : ''}} b-b0" aria-labelledby="headingThro"
                                data-parent="#accordion">
                              <div class="border px-2 pt-1">
                                  @if(!$inspection)
                                   <div class="row">
                                     <div class="col-xl-12">
                                         <div class="card-header">
                                              <div class="card-title">Inspection Details
                                                  <select class="float-right form-control w-50" id="inspection-temp">
                                                     <option value=''>select A template</option>
                                                           @foreach($templates as $template)
                                                     <option value="{{$template->id}}">{{$template->code}}</option>
                                                          @endforeach
                                                 </select>
                                              </div>
                  
                                          </div>
                                      </div> 
                                  </div> 
                                  @endif
                       <div class="row">

                         <div class="col-xl-12">
                             <div class="card custom-card">
                                    <div class="card-body pb-0">
                                        <div class="d-flex justify-content-between align-items-center mb-3" id="searchGroup">
                                        <h5 class="card-title mb-0 customer-header">Template Details</h5>

                                        </div>
                                   </div>
                                   <div class="card-body " id="temp-details">
                                      <div class="row">
                                           <input type="hidden" name="inspection_id" value="{{$inspection?->id}}" id="inspection_id" />
                                           <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                                      <label for="">Template Name</label>
                                      <input type="text" class="form-control" id="temp-name" value="{{$template?->code}}" readonly>
                                   </div>
                                  <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                                       <label for="">Template Description</label>
                                         <input type="text" class="form-control" id="temp-des" value="{{$template?->des}}" readonly>
                                   </div>
                                   <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                                      <label for="">Date</label>
                                      <input type="date" class="form-control" id="temp-date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" readonly>
                                   </div>
                                  <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                                        <label for="">Vehicle Fault</label>
                                        <input type="text" class="form-control" id="v_flaut" value="" >
                                   </div>
                                   <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                                       <label for="">Diagnostic Proccess</label>
                                       <input type="text" class="form-control" id="d_proccess" value="" >
                                   </div>
                                   <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3">
                                      <label for="">Machine Hours</label>
                                      <input type="text" class="form-control" id="m_hour" value="{{$inspection?->machine_hours}}" >
                                 </div>
                                  <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3" >
                                      <label for="">Condition Summary</label>
                                      <select  class="form-control" name="condition_summary" id="condition_summary">
                                        <option value="">Select Summary</option>
                                        <option value="Good" {{$inspection?->condition_summary == 'Good' ? 'selected' : ''}}>Good</option>
                                        <option value="Fair" {{$inspection?->condition_summary == 'Fair' ? 'selected' : ''}}>Fair</option>
                                        <option value="Poor"{{$inspection?->condition_summary == 'Poor' ? 'selected' : ''}}>Poor</option>
                                      </select>
                                 </div>
                                 <div class="col-xl-4 col-lg-4 col-md-3 col-sm-3 mb-3" >
                                      <label for="">Comment</label>
                                      <textarea class="form-control" name="condition_comment" id="condition_comment">{{$inspection?->condition_comment}}</textarea>
                                 </div>
                                        </div>
                                    <div class="row">
                                        <div id="mainAccordion" class="col-xl-12 accordion"></div>
                                    </div>
                                     @if($inspection)
                                     <div class="row">
                                           <div class="col-xl-12">
                                              <div class="card ">
                                                  <div class="card-body pb-0">
                                                      <div class="d-flex justify-content-between align-items-center mb-3" id="searchGroup">
                                                            <h5 class="card-title mb-0 customer-header">Group Details</h5>

                                                      </div>
                                                  </div>
                                                  <div class="card-body">

                                                   <div id="mainAccordion" class="accordion">
                                                      @foreach($groups as $key => $group)
                                                      <div class="card ">
                                                            <div class="accor bg-primary text-white" id="heading{{$key}}">
                                                               <h2 class="mb-0">
                                                                  <button class="btn btn-link btn-block text-left text-white" type="button" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                                                      {{$group->code}}
                                                                 </button>
                                                             </h2>
                                                          </div>
                                                          <div id="collapse{{$key}}" class="collapse" aria-labelledby="heading{{$key}}" data-parent="#mainAccordion">
                                                              <div class="pointss">
                                                                <table class="table text-nowrap table-bordered">
                                                                     <tr>
                                                                          <th>Description</th>
                                                                         <th>Condition</th>
                                                                          <th>Maintenance</th>
                                                                         <th>Comment</th>
                                                                         <th>Completed</th>
                                                                         <th>Fixed Soon</th>
                                                                         <th>Urgent</th>
                                                                      </tr>
                                                                      @foreach($group->mainpoints as $e=> $point) 
                                                                       <tr>
                           <td>
                                    <input type="hidden" name="point_group_id" id="" value="{{$group->id}}"class="form-control w-100" readonly>
                                    <input type="hidden" name="point_id" id="" value="{{$point->id}}"class="form-control w-100" readonly>
                                    <input type="text" name="point_name" id="" value="{{$point->point_name}}"class="form-control w-100" style="overflow-x: auto;"  readonly>
                            </td>
                            <td>
                                  <select name="point_condition" id="" value=""class="form-control w-100 point_comment" >
                                        <option value=""  ></option>
                                        <option value="E"  {{$point->condition=='E' ?'selected' : ''}}>E</option>
                                        <option value="G" {{$point->condition=='G' ?'selected' : ''}}>G</option>
                                        <option value="F" {{$point->condition=='F' ?'selected' : ''}}>F</option>
                                        <option value="P"  {{$point->condition=='P' ?'selected' : ''}}>P</option>
                                 </select>
                            </td>
                            <td>
                                    <select name="point_maintenance" id="" class="form-control w-100 point_input" >
                                           <option value=""></option>
                                          <option value="Yes" {{$point->maintenance_required=='Yes' ?'selected' : ''}}>Yes</option>
                                          <option value="No"  {{$point->maintenance_required=='No' ?'selected' : ''}}>No</option>
                                  </select>
                            <td>
                                    <input type="text" name="point_comment" id="" value="{{$point->comment}}"class="form-control w-100" >
                            </td>
                            <td>
                                   <input type="checkbox" class="completed" name="point_completed" {{$point->completed=='true' ||$point->completed==1 ? 'checked' : ''}}  value="0"  />
           
                            </td>
                            <td>
                                   <input type="checkbox" class="completed" name="point_fixed" value="0" {{$point->soon=='true' || $point->soon==1? 'checked' : ''}}   />
           
                            </td>
                            <td>
                                    <input type="checkbox" class="completed" name="point_urgent" value="0" {{$point->urgent =='true' || $point->urgent==1? 'checked' : ''}}  />
            
                            </td>
                     </tr>
                                                                      @endforeach
                                                                      </table>
                                                              </div>
                                                          </div>

                                                      </div>
                                                      @endforeach
                                                  </div>
                                                   </div>
                                             </div>
                                         </div>
                                    </div>
                                       @if($workOrder->status=='Start Inspection')
                                              <button class="btn btn-primary float-right mr-3"  onclick="updateinspection()">Save</button>
                                      @endif
                                     @endif
                             </div> 
                         </div>  
                 </div>
                  </div>
                  
                   </div>  
                 </div>
                  </div>
@endif
                    
                        @include('workorder.imageSection')
                        @if($workOrder->status=='Start Inspection'||$workOrder->status=='Inspection Completed'||$workOrder->status=='Work Completed'  || $workOrder->status=='Enroute'||$workOrder->status=='Start Work')
                        <div class="">
                            <div class="accor bg-primary id="heading5">
                                <h4 class="m-0">
                                    <a href="#collapse5" class="collapsed" data-toggle="collapse" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        <i class="si si-cursor-move mr-2"></i>Items
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse5"
                                class="collapse  {{$workOrder->status !='Confirmed' && $workOrder->status !='Start Inspection'?'show' : ''}} b-b0 heading-scroll"
                                aria-labelledby="heading5" data-parent="#accordion">
                                <div class="border p-3  min-width-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    
                                    <th>Action</th>
                                    <th>Type</th>
                                    <th>Product Id</th>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>Comment</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="productTableBody"></tbody>
                        </table>
                         @if($workOrder->status=="Start Inspection" )
                        <button type="button" class="btn btn-secondary btn-wave waves-effect waves-light "
                            id="addProduct">Add Product</button>
                            
                          @endif
                    </div>
                     @if($workOrder->status=="Start Inspection" )
                         <div class="form-group">
                                 <button class="btn btn-primary float-right" id="startBooking">
                                    Update Items
                                </button>&nbsp;
                    </div> 
                    @endif
                                </div>
                            </div>

                        </div>
                    @endif

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
                                <div id="collapseEight"
                                    class="collapse {{ count($warrantyRegistrations) > 0 ? 'show' : '' }} b-b0 heading-scroll"
                                    aria-labelledby="headingSeven" data-parent="#accordion">
                                    <div class="border p-3 min-width-content">
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
                                                            @if ($workOrder->status == 'Start Work')
                                                                <div class="form-group">

                                                                    @if ($warrantyRegistration->status != 3)
                                                                        <button type="button"
                                                                            class="btn ripple btn-primary text-white"
                                                                            data-toggle="modal"
                                                                            data-target="#warrantyclaimModal">Claim</button>
                                                                    @endif
                                                                    @if ($warrantyRegistration->status != 4)
                                                                        <button type="button"
                                                                            class="btn ripple btn-primary text-white"
                                                                            data-toggle="modal"
                                                                            data-target="#jumpstartModal">Jump
                                                                            Start</button>
                                                                    @endif


                                                                </div>
                                                            @endif
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
                                                                            class="btn btn-primary">Confirm</button>
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
                                                                        Are you sure you want to jump satrt this Product ?
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

                        @if ($workOrder->allocation_status == '2')
                            <form method="POST" action="{{ route('workorder.updatetechstatus') }}"
                                accept-charset="UTF-8" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="workorder" value="{{ $workOrder->id }}">
                                <input type="hidden" id="statusInput" name="status" value="">

                                <div class="d-flex justify-content-end mt-4 gap-3">
                                    @if ($workOrder->status != 'Cancel')
                                        <!-- Check if status is not 'Cancel' -->
                                        @if($workOrder->status == 'Confirmed' && !$inspection)
                                            <button type="button" class="btn btn-info"
                                               id="submitBookingInspection">
                                                Start Inspection
                                            </button>
                                             @elseif($workOrder->status == 'Confirmed' && $inspection)
                                            <button type="button" class="btn btn-info"
                                               onclick="document.getElementById('statusInput').value='Start Inspection'; this.form.submit();">
                                               
                                                Start Inspection
                                            </button>
                                              @elseif($workOrder->status == 'Start Inspection' )
                                            <button type="button" class="btn btn-success"
                                               onclick="document.getElementById('statusInput').value='Inspection Completed'; this.form.submit();">
                                               
                                                Inspection Completed
                                            </button>
                                        @elseif($workOrder->status == 'Inspection Completed')
                                            <button type="button" class="btn btn-primary"
                                                onclick="document.getElementById('statusInput').value='Enroute'; this.form.submit();">
                                               
                                                Enroute
                                            </button>
                                     @elseif($workOrder->status == 'Enroute')
                                            <button type="button" class="btn btn-info"
                                                onclick="document.getElementById('statusInput').value='Start Work'; this.form.submit();">
                                                Start Work
                                            </button>
                                
                                        @elseif($workOrder->status == 'Start Work')
                                            <button type="button" class="btn btn-success"
                                                onclick="document.getElementById('statusInput').value='Work Completed'; this.form.submit();">
                                                Work Completed
                                            </button>
                                        @endif

                                        {{-- @if ($workOrder->status != 'OnHold')
                                            <button type="button" class="btn btn-warning mx-2"
                                                onclick="document.getElementById('statusInput').value='OnHold'; this.form.submit();">
                                                On Hold
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary"
                                                onclick="document.getElementById('statusInput').value='{{ session('previous_status', $workOrder->status) }}'; this.form.submit();">
                                                Remove Hold
                                            </button>
                                        @endif --}}
                                    @endif
                                    @if($workOrder->status!=='Work Completed')
                                    <button type="button" class="btn btn-danger ml-2"
                                        onclick="if(confirm('Are you sure you want to reject?')) 
										{ document.getElementById('statusInput').value='Rejected'; this.form.submit(); }">
                                        Reject
                                    </button>
                                    @endif
                                </div>
                            </form>
                        @elseif($workOrder->allocation_status == '1')
                            <div class="d-flex justify-content-end mt-4">
                                <!-- Cancel Button -->
                                <form action="{{ route('allocate.accepttechnician') }}" method="POST" class="mr-2">
                                    @csrf
                                    <input type="hidden" name="workOrderId" value="{{ $workOrder->id }}">
                                    <input type="hidden" name="accept" value="0">
                                    @if($workOrder->status!=='Work Completed')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to reject this technician?');">
                                        Reject
                                    </button>
                                    @endif
                                </form>

                                <!-- Accept Button -->
                                <form action="{{ route('allocate.accepttechnician') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="workOrderId" value="{{ $workOrder->id }}">
                                    <input type="hidden" name="accept" value="1">
                                    <button type="submit" class="btn btn-success">
                                        Accept
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    

    @if ($workOrder->status == 'Work Completed' || $workOrder->status == 'Invoiced' || $workOrder->status == 'Paid')
        <!-- <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Invoice and Payment Details</h3>
                    </div>
                    <div class="card-body">
                        <div id="accordion" class="w-100 br-2 overflow-hidden"> -->
                            <!-- Invoice Section -->
                            <!-- <div class="">
                                <div class="accor bg-primary" id="headingInvoice">
                                    <h4 class="m-0">
                                        <a href="#collapseInvoice" class="collapsed" data-toggle="collapse"
                                            aria-expanded="false" aria-controls="collapseInvoice">
                                            <i class="si si-cursor-move mr-2"></i>Invoice
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseInvoice" class="collapse show b-b0 heading-scroll"
                                    aria-labelledby="headingInvoice" data-parent="#accordion"> -->
                                    <!-- @if (count($Workorder_scraps) > 0)
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
                                                                    <td><button class="btn btn-danger btn-sm "><a
                                                                                class="text-white "
                                                                                href="{{ route('workorderscrap.delete', $scrap->id) }}">Delete</a></button>
                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif -->
                                    <!-- @if (optional($workOrder->inv)->count() > 0)
                                        <div class="border p-3  min-width-content">
                                            <table class="table mb-0 table-bordered border-top text-center">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Invoice ID') }}</th>
                                                        <th>{{ __('Invoice Date') }}</th>
                                                        <th>{{ __('Total Amount') }}</th>
                                                        <th>{{ __('Discount') }}</th>
                                                        <th>{{ __('Final Amount') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($invoices as $item)
                                                        <tr>
                                                            <td>{{ $item->invoice_id }}</td>
                                                            <td>{{ $item->invoice_date }}</td>
                                                            <td>{{ $item->total }}</td>
                                                            <td>{{ $item->discount }}</td>
                                                            <td>{{ $item->final_amount }}</td>
                                                            <td>{{ $item->status }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else -->
                                        <!-- @if (count($Workorder_scraps) == 0)
                                            <div class="form-group mt-3">
                                                <a class="btn btn-primary  ml-20 customModal2" href="#"
                                                    data-size="lg"
                                                    data-url="{{ url('workorder/scrap/' . $workOrder->id . '/tech') }}"
                                                    data-title="Scrap Datails">
                                                    {{ __('Scrap ') }}
                                                </a>
                                        @endif -->
                                        <!-- <a href="{{ url('technicianbookinginvoice/' . $workOrder->id) }}"
                                            class="btn btn-primary mt-3">Invoice</a>
                                </div>
    @endif
    </div>
    </div> -->

    <!-- @if ($workOrder->status == 'Invoiced' || $workOrder->status == 'Paid')
        <div class="">
            <div class="accor bg-primary" id="headingPayment">
                <h4 class="m-0">
                    <a href="#collapsePayment" class="collapsed" data-toggle="collapse" aria-expanded="false"
                        aria-controls="collapsePayment">
                        <i class="si si-cursor-move mr-2"></i>Payment
                    </a>
                </h4>
            </div>
            <div id="collapsePayment" class="collapse b-b0 heading-scroll" aria-labelledby="headingPayment"
                data-parent="#accordion">
                <div class="border p-3  min-width-content">
                    @if ($payments->count() > 0)
                        <table class="table mb-0 table-bordered border-top text-center">
                            <thead>
                                <tr>
                                    <th>{{ __('Payment ID') }}</th>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Invoice ID') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Amount Paid') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->payment_date }}</td>
                                        <td>{{ $item->invoice }}</td>
                                        <td>{{ $item->payment_method }}</td>
                                        <td>{{ array_sum(explode(',', $item->paid_amount)) }}</td>
                                        <td>{{ $item->status }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No payment found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <div class="form-group">
                            <a href="{{ url('transferpaymentinvoicetechnician/' . $workOrder->id) }}"
                                class="btn btn-primary">Payment</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif -->
    <!-- </div>
    </div>
    </div>
    </div>
    </div>
    @endif -->




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

 <script>
          function collectProducts() {
                let products = [];

                $('#productTableBody tr').each(function() {
                    let product = {
                        product_id: $(this).find('.product-id')
                            .val(), // Retrieve the hidden product ID
                        item_type: $(this).find('.item-type').val(),
                        product_name: $(this).find('.product-name-cell').text(),
                        item_no:$(this).find('.description-cell').text(),
                        quantity: $(this).find('.qty-cell').text(),
                        comment: $(this).find('.comment-cell').text(),
                    };
                    products.push(product);
                });

                return products;
            }
			    function collectData() {
                let formData = {
                    booking_id: $('#booking_id').val(),
                    products: collectProducts(),

                };

                return formData;
            }

  $('#startBooking').click(function(e) {
                e.preventDefault();

       
                let formData = collectData();




                $.ajax({
                    url: "{{ route('quotation.update') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                                       Swal.fire({
                    title:'Items Updated Successfully!',
          
                   icon: "success",
                       draggable: true
                })
                .then((willDelete) => {
                    if (willDelete) {

                      } else {
                      swal("Your imaginary file is safe!");
                      }
                    });
                    },
                    error: function(error) {
                        alert('Error starting booking');
                        console.error(error);
                    }
                });




        
            });
          
			</script>
			<script>
  let items = [];
    async function fetchAndShowFirstProduct() {
 
            if (items.length === 0) {
            const quotProducts = await fetchQuotProduct();
            
            if (quotProducts.length > 0) {

                for (let i = 0; i < quotProducts.length; i++) {
                    addProductToTable(quotProducts[i], 1);
                }
            }
        }
        }
        console.log(items);
            async function fetchQuotProduct() {
            const workOrderId = $("#workorder_id").val();
            const bookingId = $("#booking_id").val();
            const status=@json($quotationstatus->status);

            try {
                
                const response = await fetch(
                    `/getquotation/workorder?status=${status}&workorder_id=${workOrderId}`
                );
                const products = await response.json();
                console.log(products);
                return products.length > 0 ? products : null;
            } catch (error) {
                console.error('Error fetching the quote products:', error);
                return null;
            }
        } 
        fetchAndShowFirstProduct();
        document.getElementById('addProduct').addEventListener('click', async function() {
       

            const firstProduct = await fetchFirstProduct();
            if (firstProduct) {
                addProductToTable(firstProduct);
            }
        });
            async function fetchFirstProduct(vehicleId) {
           
            try {
                const response = await fetch(
                    `/searchproducts`
                );
                const products = await response.json();
                
                return products.length > 0 ? products[0] : null;
            } catch (error) {
                console.error('Error fetching the first product:', error);
                return null;
            }
        }
            async function fetchQuotProduct() {

            const workOrderId = $("#workorder_id").val();
         let status=@json($quotationstatus->status);
            try {
                const response = await fetch(
                    `/getquotation/workorder?workorder=${workOrderId}&status=${status}`
                );
                const products = await response.json();
                console.log(products);
                return products.length > 0 ? products : null;
            } catch (error) {
                console.error('Error fetching the quote products:', error);
                return null;
            }
        }    
    
      async function fetchSimilarProducts(query, itemType) {
            try {
                const response = await fetch(
                    `/searchproducts?search=${encodeURIComponent(query)}&itemType=${encodeURIComponent(itemType)}`
                );
                const products = await response.json();
                return products;
            } catch (error) {
                console.error('Error fetching similar products:', error);
                return [];
            }
        }
         function addProductToTable(productData) {
            if (!productData  ) {
                console.error("Invalid product data:", productData);
                return;
            }



            const unitPrice = parseFloat(productData.price) || 0;
            const quantity = productData.qty || 1;
            const discountPercentage = parseFloat(productData.discount_percentage) || productData.gstprice || 0;

            const discount = (quantity * unitPrice) * (discountPercentage / 100);
            const lineTotal = (unitPrice * quantity) - discount;

            const taxPercentage = parseFloat(productData.tax) || 0;
            const taxAmount = lineTotal * (taxPercentage / 100);
            const totalAmountIncTax = lineTotal + taxAmount;

            const item = {
                id: productData.id || productData.product_id,
                productName: productData.product_name,
                item_no: productData.item_no || '',
                unitPrice,
                quantity,
                uom: productData.uom,
				comment: productData.comment || '',
                lineTotal,
                item_type: productData.item_type || '',
                discount_percentage: discountPercentage,
                warranty: productData.warranty || 'N/A',
                tax_percentage: taxPercentage,
                tax_amount: taxAmount.toFixed(2),
                total_amount_inc_tax: totalAmountIncTax.toFixed(2),
            };

            items.push(item);
            renderTable();
          
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
                <select class="item-type" data-index="${index}">
                    <option value="inventory" ${item.item_type === 'inventory' ? 'selected' : ''}>Inventory</option>
                    <option value="services" ${item.item_type === 'services' ? 'selected' : ''}>Service</option>
                    <option value="noninventory" ${item.item_type === 'noninventory' ? 'selected' : ''}>Non Inventory</option>
                </select>
            </td>
            <td class="description-cell" data-index="${index}">${item.item_no}</td>
            <td class="product-name-cell" data-index="${index}">${item.productName}</td>
            <td class="qty-cell" data-index="${index}">${item.quantity}</td>
            <td class="comment-cell" data-index="${index}">${item.comment}</td>
            <input type="hidden" class="product-id" value="${item.id}" />
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
                    
                });
            });
        }

        function handleItemTypeChange() {
            document.querySelectorAll('.item-type').forEach(function(select) {
                select.addEventListener('change', async function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const newItemType = this.value;
                    console.log(newItemType);
                    if(newItemType=='noninventory')
                    {
                        items[index].productName = '';
                        items[index].id = '';
                        items[index].item_type = 'noninventory';
                        items[index].item_no = '';
                        items[index].unitPrice =  0;
                        items[index].discount_percentage = 0;
                        items[index].warranty =  'N/A';
                        items[index].quantity =  0;
                        items[index].item_type = newItemType;

                   
                        items[index].lineTotal = 0;
                    }
                    else{
                    // Fetch the first product of the selected item type
                    const firstProduct = await fetchFirstProductByType(newItemType);

                    if (firstProduct) {
                        items[index].productName = firstProduct.product_name;
                        items[index].description = firstProduct.type || '';
                        items[index].unitPrice = parseFloat(firstProduct.price) || 0;
                        items[index].discount_percentage = parseFloat(firstProduct
                            .discount_percentage) ||
                            0;
                        items[index].warranty = firstProduct.warranty || 'N/A';
                        items[index].item_type = newItemType;

                        // Recalculate the line total
                        const quantity = items[index].quantity;
                        const discount = (quantity * items[index].unitPrice) * (items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * quantity) - discount;
                    }
                      
                        
                    }
                      renderTable();
                });
            });
        }

        async function fetchFirstProductByType(itemType) {
            try {
                console.log(itemType);
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
     document.querySelectorAll('.comment-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentComment = items[index].comment;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentComment;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newComment = this.value.trim();
                        items[index].comment = newComment;
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
                        items[index].tax_amount = taxAmount.toFixed(2);
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(2);

                        renderTable();
                        
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
                        items[index].tax_amount = taxAmount.toFixed(2);

                        // **Recalculate Total Amount (Including Tax)**
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(2);

                        renderTable();
                        
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
                        items[index].tax_amount = taxAmount.toFixed(2);

                        // **Recalculate Total Amount (Including Tax)**
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(2);

                        renderTable();
                        
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
                    input.value = currentLineTotal.toFixed(2);

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newLineTotal = parseFloat(this.value) || 0;
                        items[index].lineTotal = newLineTotal;

                        // Recalculate tax and total amount including tax
                        const taxPercentage = items[index].tax_percentage || 0;
                        const taxAmount = newLineTotal * (taxPercentage / 100);
                        items[index].tax_amount = taxAmount.toFixed(2);
                        items[index].total_amount_inc_tax = (newLineTotal + taxAmount).toFixed(2);

                        renderTable();
                        
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
        </script>
        <script>


     let insp =document.getElementById("inspection-temp");
     let name=document.getElementById('temp-name');
     let des=document.getElementById('temp-des');
     let date=document.getElementById('temp-date');
     let template;

     console.log(name);
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
        <button class="btn btn-link text-white" data-toggle="collapse"  style="overflow-x: auto;"   data-target="#collapseMain${mainIndex}" aria-expanded="false" aria-controls="collapseMain${mainIndex}">
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


      nestedCard.classList.add('points','table-responsive');
       const nestedTable = document.createElement('table');
        nestedTable.classList.add('table', 'text-nowrap', 'table-bordered');

        nestedTable.innerHTML = `  
                              <tr>
                                <th>Description</th>
                                <th>Condition</th>
                                <th>Maintenance</th>
                                <th>Comment</th>
                                <th>Completed</th>
                                <th>Fixed Soon</th>
                                <th>Urgent</th>
                             </tr>`;
    // Loop through nested items
    mainItem.editpoints.forEach((nestedItem, nestedIndex) => {

        nestedTable.innerHTML += `<tr>
        <td>
        <input type="hidden" class="point_id" name="point_group_id" value="${mainItem.id}"/>
            <input type="hidden" class="point_id" name="point_id" value="${nestedItem.id}"/>
             <input type="text" name="point_name" id="" value="${nestedItem.point_des}"class="form-control w-100" style="overflow-x: auto;"  readonly>
          </td>
          <td>
           
             <select name="point_condition" id="point_condition" class="form-control w-100 point_comment" >
             <option value=""></option>
             <option value="E">E</option>
             <option value="G">G</option>
             <option value="F">F</option>
             <option value="P">P</option>
             </select>
          </td>
      
          <td>

             <select name="point_maintenance" id="" class="form-control w-100 point_input" >
             <option value=""></option>
             <option value="Yes">Yes</option>
             <option value="No">No</option>
             </select>
          </td>
          <td>

             <input type="text" name="point_comment" id="" class="form-control w-100 point_input" >
          </td>
            
           
          <td>
          
            <input type="checkbox" class="completed point_completed" name="point_completed" value="0"  />
            </td>
            <td>
          
            <input type="checkbox" class="completed point_fixed" name="point_fixed" value="0"  />
            </td>
            <td>
          
            <input type="checkbox" class="completed point_urgent" name="point_urgent" value="0"  />
            </td>
            
                </tr>`;



    });
    nestedCard.appendChild(nestedTable);
    nestedAccordion.appendChild(nestedCard);
    mainCollapse.appendChild(nestedAccordion);
    mainCard.appendChild(mainHeader);
    mainCard.appendChild(mainCollapse);

    mainAccordion.appendChild(mainCard);
  });
      
                    }
            });
            });


    document.getElementById('submitBookingInspection').addEventListener('click',  function() {
       

       if (!insp.value) {
        alert("Please select a atemplate first");
        return;
        }
    $.ajax({
        url: '{{ route('bookinginspection.store') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            booking_id: $("#booking_id").val(),
            customer_id: $('#customer_id').val(),
            equipment: $("#equipment_id").val(),
            template:$("#inspection-temp").val(),
            workorder_id:$("#workorder_id").val(),
            condition_summary:$("#condition_summary").val(),
            condition_comment:$("#condition_comment").val(),
            points:collectPoints("points")

        },
        success: function(response) {
            console.log(response);
            alert('Inspection Created Successfully');
            location.reload();

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('There was an error submitting the booking inspection.');
        }
    });
});
      function updateinspection() {
//    console.log(document.querySelectorAll('.pointss .row'));
     $.ajax({
        url: '{{ route('updatebookinginspection.store') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            inspection_id: $("#inspection_id").val(),
            v_flaut: $("#v_flaut").val(),
            m_hour: $("#m_hour").val(),
            d_proccess:$("#d_proccess-temp").val(),
            condition_summary:$("#condition_summary").val(),
            condition_comment:$("#condition_comment").val(),
            points:collectPoints("pointss")

        },
        success: function(response) {
            console.log(response);
                           Swal.fire({
                    title:'Inspection Updated Successfully!',
               
                   icon: "success",
                       draggable: true
                })
                .then((willDelete) => {
         if (willDelete) {
   
        } else {
            swal("Your imaginary file is safe!");
        }
          });
            location.reload();

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('There was an error submitting the booking inspection.');
        }
    });
}
 function collectPoints(classname)
            {const points = [];
                const accordionRoot = document.querySelectorAll(`.${classname} table tr`);
                accordionRoot.forEach(e=>{
                const inputs = e.querySelectorAll('input , select');
                
                let point={};
                inputs.forEach(input => {
                
                 const key = input.name ;
  
               if (input.matches("select")) {
                   point[key] =  input.value;
                          }
                          else if (input.type === 'checkbox' ) {
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
                        $("#error-message").text(xhr.responseJSON.error);
                    }
                });
            }
        });
    });
     
 
</script>  



@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
   
    $(document).ready(function() {
        function validateFormData(formData) {
            let errors = [];
            // Uncomment these if validation is needed
            // if (!formData.invoiceNo) errors.push("Invoice No is required.");
            // if (!formData.customerOrderNumber) errors.push("Customer Order Number is required.");
            // if (!formData.jobCardNo) errors.push("Job Card No. is required.");
            // if (!formData.postDate) errors.push("Post Date is required.");
            // if (!formData.invoiceType) errors.push("Invoice Type is required.");
            // if (!formData.followUpDate) errors.push("Follow Up Date is required.");
            // if (!formData.odometer) errors.push("Odometer is required.");
            // if (!formData.hours) errors.push("Hours are required.");
            // if (!formData.nextServiceKms) errors.push("Next Service KMS is required.");
            // if (!formData.status) errors.push("Status is required.");
            // if (!formData.jobStatusComment) errors.push("Job Status Comment is required.");
            // if (!formData.customerSource) errors.push("Customer Source is required.");
            // if (!formData.description) errors.push("Description is required.");

            return errors;
        }

        function collectFormData() {
            return {
                customerId: $('#customerId').val(),
                workOrderId: $('#workOrderId').val(),
                invoiceNo: $('#invoiceNo').val(),
                customerOrderNumber: $('#customerOrderNumber').val(),
                jobCardNo: $('#jobCardNo').val(),
                postDate: $('#postDate').val(),
                dueDate: $('#dueDateInput').val(),
                invoiceType: $('#invoiceType').val(),
                paymentTerms: $('#payment-terms').val(),
                followUpDate: $('#followupDate').val(),
                totalamount: $('#totalamount').val(),
                nextServiceKms: $('#nextservice').val(),
                status: $('#status').val(),
                customerSource: $('#customersource').val(),
                description: $('#description').val(),
                paymentType: $('#invoice-switch').is(":checked") ? "Cheque" : "Cash"
            };
        }

        $('#startPayment').click(function() {
            let formData = collectFormData();
            let errors = validateFormData(formData);

            if (errors.length > 0) {
                alert(errors.join("\n"));
                return;
            }
            $.ajax({
                url: "{{ route('invoice.finalstore') }}",
                method: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert("Invoice Created Successfully");
                        location.reload()
                    } else {
                        alert(response.message);
                    }
                },
                error: function(error) {
                    console.error(error);
                    alert("An error occurred while creating the invoice.");
                }
            });
        });
    });
</script>


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

    @include('workorder.workOrderMap')
@endsection
