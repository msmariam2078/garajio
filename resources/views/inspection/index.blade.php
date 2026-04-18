@extends('layouts.master')
<?php
use App\Models\vehicle_make;
use App\Models\vehicle_model;
?>

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Inspection Sheet</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    <button type="button" class="btn btn-primary"><a href="{{ route('inspection.create') }}"
                            class='text-white'>Create Inspection Sheet</a></button>

                </div>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')
    <!-- row opened -->
    <div class="row row-sm">

        <div class="col-xl-12">
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mg-b-0 text-md-nowrap datatbl-advance">
                            <thead>
                                <tr>
									<th class="d-none"></th>
                                    <th>{{ __('Inspection Id') }}</th>
                                    <th>{{ __('Booking Id') }}</th>
                                    <th>{{ __('Customer Id') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Equipment') }}</th>
                                    <th>{{ __('Make') }}</th>
                                    <th>{{ __('Model') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inspections->sortByDesc('id')->values() as $key => $inspection)
                                    <tr>
										<td class="d-none"></td>
                                        <th>#INS-{{ $inspection->id }}</th>
                                        <th>#Bo-{{ $inspection->booking }}</th>
                                        <th>#CLI-{{ $inspection->customer }}</th>
                                        <th>{{ $inspection->customerdetails?->full_name }}</th>
                                        <th>{{ $inspection->customerdetails?->full_phone }}</th>
                                        <th>{{ $inspection->vehicledetails?->rego ?? '' }}</th>

                                        <th>{{ $inspection->vehicledetails?->vehicle_makes?->make_name }}</th>
                                        <th>{{ $inspection->vehicledetails?->vehicle_models?->model_name }}</th>
                                        <th>
                                            @if ($inspection->status == 'open')
                                                <span class="badge bg-warning" style="color:white;width:75px;">Open</span>
                                            @elseif($inspection->status == 'quote')
                                                <span class="badge bg-secondary"
                                                    style="color:white;width:75px;">Quotation</span>
                                            @elseif($inspection->status == 'approved')
                                                <span class="badge bg-secondary"
                                                    style="color:white;width:75px;">Approved</span>
                                            @elseif($inspection->status == 'cancelled')
                                                <span class="badge bg-danger"
                                                    style="color:white;width:75px;">Cancelled</span>
                                            @endif

                                            <!-- <form action="{{ route('inspection.updateStatus', $inspection->id) }}" method="POST" class="status-form">
                                                    @csrf
                                                        <label class="switch">
                                                            <input type="checkbox" name="status" class="toggle-status" onchange="this.form.submit()" {{ $inspection->status == '1' ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                </form> -->
                                        </th>
                                        <th>


                                            {!! Form::open(['method' => 'DELETE', 'route' => ['inspection.destroy', $inspection->id]]) !!}
                                            <a href="/inspectionbooking/{{ $inspection->booking }}">
                                                <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>
                                            <a class="text-success" href="{{ route('inspection.show', $inspection->id) }}">
                                                <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>
                                            <a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ __('Delete') }}" href="#">
                                                <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                    style='width:25px;height:25px;'>
                                            </a>
                                            {!! Form::close() !!}
                                        </th>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->




    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
