@extends('layouts.master')
@php
$profile=asset(Storage::url('upload/profile/'));
@endphp
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Payment')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

       

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
                    <table class="table text-md-nowrap datatbl-advance">
                        <thead>
                            <tr class='mb-5'>
                                <th>{{__('Workorder ID')}}</th>
                                <th>{{__('Payment ID')}}</th>
                                <th>{{__('Invoice ID')}}</th>
                                <th>{{__('Customer ID')}}</th>
                                <th>{{__('Customer Name')}}</th>
                                <th>{{__('Customer Phone')}}</th>
                                <th>{{__('Payment Method')}}</th>

                                <th>{{__('Payment Amount')}}</th>
                                <th>{{__('Status')}}</th>
                               
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($payments as $key=>$payment)
                            <tr>
                                <th>
                                  {{$payment->invoices->wo_id??''}}
                                </th>
                                <th>#PAY-{{ $key+1 }}</th>
                                <th>

                                </th>
                                <th>{{$payment->invoice}}</th>
                                <th>{{ $payment->user->first_name }}</th>
                                <th>{{ $payment->user->phone_number }}</th>
                                <th>{{ $payment->payment_method }}</th>
                                <th>{{ $payment->paid_amount }}</th>
                                <th>{{ $payment->status }}</th>
                               
                            </tr>
                            
                            @endforeach



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/div-->
@endsection
@section('js')



<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
@endsection