@extends('layouts.master')
@section('css')
<!--Internal Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
    rel="stylesheet">
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ clientPrefix() }}
                {{ !empty($client->clients) ? $client->clients->client_id : '' }}
            </h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/ {{ __('Details') }}</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <a href="{{ route('client.index') }}" class="btn btn-primary ml-2">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

@error('vehicle_id')
<span class="text-danger" style="font-size: 20px;">{{ $message }}</span>
@enderror

<div class="row row-sm">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="tabs-menu ">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs profile navtab-custom panel-tabs" role="tablist">
                        <li class="nav-item">
                            <a href="#customer" data-bs-toggle="tab" class="nav-link active" role="tab">
                                <span class="hidden-xs">Customer</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#vehicle" data-bs-toggle="tab" class="nav-link" role="tab">
                                <span class="hidden-xs">Vehicle</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#bookings" data-bs-toggle="tab" class="nav-link" role="tab">
                                <span class="hidden-xs">Bookings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#workorder" data-bs-toggle="tab" class="nav-link" role="tab">
                                <span class="hidden-xs">WorkOrder History</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#invoice" data-bs-toggle="tab" class="nav-link" role="tab">
                                <span class="hidden-xs">Invoices</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#warranty" data-bs-toggle="tab" class="nav-link" role="tab">
                                <span class="hidden-xs">Warranty Registration Section</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content  border border-top-0 p-4 br-dark">
                        <!-- Customer Tab -->
                        <div class="tab-pane border-0 p-0 active show" id="customer">
                            <style>
                            .card-body {
                                padding: 0 !important
                            }

                            .card-header h5 {
                                font-size: 1.35rem !important
                            }

                            .card-body .ml-5 {
                                margin-top: 1rem !important;
                            }
                            </style>

                            <div class="card">

                                <div class="card-body shadow">
                                    <div class="card-header bg-primary text-light py-2">
                                        <h5>Personal Details</h5>
                                    </div>
                                    <div class="row ml-5">
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Name') }}</h6>
                                                <strong class="mb-20">{{ $client->first_name }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Email') }}</h6>
                                                <p class="mb-20">{{ $client->email }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Phone Number') }}</h6>
                                                <p class="mb-20">
                                                    +{{preg_replace('/[^0-9]/', '',  $client->ccm )}}{{ $client->phone_number }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Company') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->clients) ? $client->clients->company : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Payment Terms Code') }}</h6>
                                                <p class="mb-20">{{ $client->payment_terms_code ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Payment Method Code') }}</h6>
                                                <p class="mb-20">{{ $client->payment_method_code ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Vat Bus Posting Group') }}</h6>
                                                <p class="mb-20">{{ $client->vat_bus_posting_group ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Customer Posting Group') }}</h6>
                                                <p class="mb-20">{{ $client->customer_posting_group ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Gen Bus Posting Group') }}</h6>
                                                <p class="mb-20">{{ $client->gen_bus_posting_group ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">

                                <div class="card-body shadow">
                                    <div class="card-header bg-info text-light py-2">
                                        <h5>{{ __('Service Address') }}</h5>
                                    </div>
                                    <div class="row ml-5">
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Country') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->clients) ? $client->clients->service_country : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('State') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->clients) ? $client->clients->service_state : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('City') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->clients) ? $client->clients->service_city : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Zip Code') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->clients) ? $client->clients->service_zip_code : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Address') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->clients) ? $client->clients->service_address : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">

                                <div class="card-body shadow">
                                    <div class="card-header bg-secondary text-light py-2">
                                        <h5>{{ __('Billing Address') }}</h5>


                                    </div>
                                    <div class="row ml-5">
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Billing Country') }}</h6>
                                                <p class="mb-20">
                                                    {{-- @dd($client->billInfo); --}}
                                                    {{ !empty($client->billInfo) ? $client->billInfo->country : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Billing State') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->billInfo) ? $client->billInfo->billing_state : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Billing City') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->billInfo) ? $client->billInfo->city : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Billing Zip Code') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->billInfo) ? $client->billInfo->billing_zip_code : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Billing Address') }}</h6>
                                                <p class="mb-20">
                                                    {{ !empty($client->billInfo) ? $client->billInfo->service_location : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-danger text-light py-2">
                                    <h5>{{ __('Address') }}</h5>

                                </div>
                                <div class="card-body shadow">
                                    @if ($client->clients != null && $client->clients->addresses != null)
                                    <div class="row ml-5">
                                        @php
                                        $addresses = json_decode($client->clients->addresses, true);
                                        @endphp
                                        @for ($i = 1; $i < count($addresses) / 5; $i++) <div class="col-md-12">
                                            <div class="detail-group mb-3">
                                                <h6 style="color:#FF8080">{{ __('Address') }} {{ $i }}
                                                </h6>
                                                <p class="mb-20">{{ $addresses[$i * 5]['address'] ?? '-' }}</p>
                                            </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="detail-group mb-3">
                                            <h6 style="color:#FF8080">{{ __('City') }}</h6>
                                            <p class="mb-20">{{ $addresses[$i * 5 + 1]['city'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="detail-group mb-3">
                                            <h6 style="color:#FF8080">{{ __('State') }}</h6>
                                            <p class="mb-20">{{ $addresses[$i * 5 + 2]['state'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="detail-group mb-3">
                                            <h6 style="color:#FF8080">{{ __('Country') }}</h6>
                                            <p class="mb-20">{{ $addresses[$i * 5 + 3]['country'] ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="detail-group mb-3">
                                            <h6 class="text-danger">{{ __('Zip/Postal Code') }}</h6>
                                            <p class="mb-20">{{ $addresses[$i * 5 + 4]['zip_code'] ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    @endfor
                                </div>
                                @else
                                <div class="col-md-12">
                                    <p>{{ __('No addresses available.') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Tab -->
                    <div class="tab-pane" id="vehicle">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Registration Number') }}</th>
                                        <th>{{ __('Make') }}</th>
                                        <th>{{ __('Model') }}</th>

                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehicles as $kay => $vehicle)
                                    <tr>
                                        <td>{{ $kay + 1 }}</td>
                                        <td>{{ $vehicle->rego }} </td>
                                        <td>{{ $vehicle->vehicle_makes?->make_name }} </td>
                                        <td>{{ $vehicle->vehicle_models?->model_name }} </td>

                                        <td>
                                            <form action="{{ route('vehicle.updateStatus', $vehicle->id) }}"
                                                method="POST" class="status-form">
                                                @csrf
                                                <label class="custom-switch">
                                                    <input type="hidden" name="status" value="0">
                                                    <input type="checkbox" name="status" class="toggle-status" value='1' onchange="this.form.submit()"
                                                        {{ $vehicle->status == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td> @can('view vehicle')
                                            <a class="text-warning customModal" data-bs-toggle="tooltip" data-size="lg"
                                                data-bs-original-title="{{ __('Details') }}" href="#"
                                                data-url="{{ route('vehicle.show', \Illuminate\Support\Facades\Crypt::encrypt($vehicle->id)) }}"
                                                data-title="{{ __('Vehicle') }}">
                                                <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>
                                            @endcan
                                            @can('edit vehicle')
                                            <a class="text-success customModal" data-bs-toggle="tooltip" data-size="lg"
                                                data-bs-original-title="{{ __('Edit') }}" href="#"
                                                data-url="{{ route('vehicle.edit', $vehicle->id) }}">
                                                <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>
                                            @endcan
                                            @endforeach
                                        </td>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bookings Tab -->
                    <div class="tab-pane" id="bookings">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Booking ID') }}</th>
                                        <th>{{ __('Requested Date') }}</th>
                                        <th>{{ __('Requested Time') }}</th>
                                        <th>{{ __('Service Group') }}</th>

                                        <th>{{ __('Vehicle Registration No') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $key => $booking)
                                    <tr>
                                        <th>Booking-{{ $key + 1 }}</th>
                                        <th>{{ $booking->requested_date }}</th>
                                        <th>{{ $booking->requested_time }}</th>
                                        <th>{{ $booking->servicegroupdata ? $booking->servicegroupdata->title : '-' }}
                                        </th>

                                        <th>Vehicle: {{ $booking->vehicless?->name }}
                                            ({{ $booking->vehicless?->rego }})
                                        </th>
                                        <th>{{ $booking->status }}</th>
                                        <th>
                                            <a class="" href="{{ route('booking.edit', $booking->id) }}">
                                                <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>
                                            <a class="" href="#">
                                                <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>

                                        </th>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- WorkOrder History Tab -->
                    <div class="tab-pane" id="workorder">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Service Group</th>
                                        <th>V Reg</th>
                                        <th>Technician</th>
                                        <th>Booking Schedule Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workorders as $key => $workorder)
                                    <tr>
                                        <td>#WO-{{ $workorder->id }}</td>
                                        <td>{{ strtoupper($workorder->client->client_type) }}</td>
										<td>{{App\Models\ServiceGroups::where('id',json_decode($workorder->service_group,true))->first()->name??""}}</td>
										<td>{{App\Models\Vehicle::where('id',json_decode($workorder->vehicle,true))->first()->rego??""}}</td>
										<td>
											{{App\Models\User::where('id',json_decode($workorder->technician,true))->first()->first_name??""}}
											{{App\Models\User::where('id',json_decode($workorder->technician,true))->first()->last_name??""}}
										</td>
                                    	<td>{{App\Models\Booking::where('id',json_decode($workorder->booking,true))->first()->booking_date??""}}</td>
                                        <td>{{ $workorder->status }}</td>
                                        <td>
                                            <div class="cart-action">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['workorder.destroy',
                                                $workorder->id]]) !!}
                                                <a class="text-success"
                                                    href="{{ route('workorder.show', $workorder->id) }}">
                                                    <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                                <a class="text-success"
                                                    href="{{ route('workorder.edit', $workorder->id) }}">
                                                    <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                                <a class="text-danger confirm_dialog" href="#">
                                                    <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Invoice Tab -->
                    <div class="tab-pane" id="invoice">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>

                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Invoice Date') }}</th>


                                        <th>{{ __('V.Reg') }}</th>
                                        <th>{{ __('Workorder ') }}</th>
                                        <th>{{ __('Technician ') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Total') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $key => $invoice)
                                    <tr>
                                        <td>{{ invoicePrefix() }}{{ $key + 1 }}</td>
                                        <td>{{ $invoice->invoice_date }}</td>

                                        <td>Dummy V.Reg</td>
                                        <td>{{ workOrderPrefix() . $invoice->wo_id }}</td>
                                        <td>{{ technicianPrefix() . $invoice->wo_id }}</td>
                                        <td>{{ $invoice->status }}</td>
                                        <td>{{ $invoice->final_amount }}</td>






                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Warranty Registration Tab -->
                    <div class="tab-pane" id="warranty">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Work order ID') }}</th>
                                        <th>{{ __('Vehicle ID') }}</th>

                                        <th>{{ __('Product Name') }}</th>

                                        <th>{{ __('Invoice ID') }}</th>
                                        <th>{{ __('Warranty Period') }}</th>
                                        <th>{{ __('Warranty Start Date') }}</th>
                                        <th>{{ __('Warranty End Date') }}</th>
                                        <th>{{ __('Status') }}</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warrentyRegistrations as $key => $warrentyRegistration)
                                    <tr>
                                        <td>#REG000{{ $key + 1 }}</td>
                                        <td>#WO00{{ $warrentyRegistration->workorder?->id }} </td>
                                        <td>#VO00{{ $warrentyRegistration->vehicle }} </td>

                                        <td>{{ $warrentyRegistration->product?->product_name }} </td>
                                        <td>{{ $warrentyRegistration->workorder?->inv?->invoice_id }} </td>
                                        <td>{{ $warrentyRegistration->warranty_period }} months </td>
                                        <td>{{ $warrentyRegistration->warranty_start_date }} </td>
                                        <td>{{ $warrentyRegistration->warranty_end_date }}</td>
                                        <td>{{ $warrentyRegistration->status }} </td>






                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div><!-- End Tab Content -->
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endsection