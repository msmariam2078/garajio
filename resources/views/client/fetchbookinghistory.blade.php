@extends('layouts.master')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
    rel="stylesheet">


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Client's bookings</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

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
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0 text-md-nowrap">
                        <thead>
                            <tr>
                                <th>{{__('Booking ID')}}</th>
                                <th>{{__('Requested Date')}}</th>
                                <th>{{__('Requested Time')}}</th>
                                <th>{{__('Service Group')}}</th>

                                <th>{{__('Vehicle Registration No')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                            <tr>
                                <th>Booking-{{ $booking->id }}</th>
                                <th>{{ $booking->requested_date }}</th>
                                <th>{{ $booking->requested_time }}</th>
                                <th>{{ $booking->servicegroupdata ?$booking->servicegroupdata->title :'-' }}</th>

                                <th>Vehicle: {{ $booking->vehicless->name }} ({{ $booking->vehicless->rego }})</th>
                                <th>{{ $booking->status }}</th>
                                <th>
                                    <a class="" href="{{ route('booking.edit',$booking->id) }}">
                                        <img src="{{URL::asset('assets/img/icons/edit.svg')}}"
                                            style='width:25px;height:25px;'>
                                    </a>
                                    <a class="" href="#">
                                        <img src="{{URL::asset('assets/img/icons/trash.svg')}}"
                                            style='width:25px;height:25px;'>
                                    </a>

                                </th>
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
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

@endsection