@extends('layouts.master')
@section('title','Technician Log')
@section('css')




@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Technician Log')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
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
                    <table class="table text-md-nowrap" id="example2">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Log</th>
                                <th>Created Date and Time</th>
                                <th>Session ID</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Work Order ID</th>
                                <th>Requested Time</th>
                                <th>Accepted Time</th>
                                <th>En Route Time</th>
                                <th>Start Time</th>
                                <th>Finish Time</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($technicianlog as $item)
                            <tr>
                                <td>{{ $item->user?->first_name }} {{ $item->user?->last_name }}</td>
                                <td>{{ $item->log }}</td>
                                <td>{{ $item->created_dateandtime }}</td>
                                <td>{{ $item->sessionid }}</td>
                                <td>{{ $item->checkin }}</td>
                                <td>{{ $item->checkout }}</td>
                                <td>{{ $item->workorderid }}</td>
                                <td>{{ $item->requested_time }}</td>
                                <td>{{ $item->accepted_time }}</td>
                                <td>{{ $item->en_rout_time }}</td>
                                <td>{{ $item->start_time }}</td>
                                <td>{{ $item->finish_time }}</td>
                                <td>{{ $item->date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- bd -->
            </div><!-- bd -->
        </div><!-- bd -->
    </div>
</div>



@endsection
@section('js')



<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>

<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>



@endsection