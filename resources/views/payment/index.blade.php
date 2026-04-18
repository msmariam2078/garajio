@extends('layouts.master')
@section('title', 'Payment')
@php
    $profile = asset(Storage::url('upload/profile/'));
@endphp
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        .action-buttons {
            white-space: nowrap;
            /* Keep all buttons in one line */
        }

        .action-buttons a,
        .action-buttons form {
            display: inline-block;
            /* Keep <a> and <form> inline */
            vertical-align: middle;
            /* Align icons/buttons nicely */
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Payment') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                @can('create payment')
                    <a class="btn btn-primary  ml-20 " href="{{ route('payment.create') }}"> <i class="ti-plus mr-1"></i>
                        {{ __('Create Payment') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>
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
                                    <th class="d-none">Sl</th>
                                    <th>{{ __('Payment ID') }}</th>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Customer Name') }}</th>

                                    <th>{{ __('Invoice ID') }}</th>
                                    <th>{{ __('Work Order ID') }}</th>
                                    <th>{{ __('Payment Amount') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Payment Ref. No') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $statusColors = [
                                        'Completed' => 'badge-danger',
                                        'Quote' => 'badge-success',
                                        'Booking' => 'badge-secondary',
                                        'Invoiced' => 'badge-warning',
                                    ];
                                @endphp
                                @foreach ($payments->sortByDesc('id')->values() as $key => $payment)
                                    <tr>
                                        <td class="d-none" data-order="{{ $key + 1 }}">{{ $key + 1 }}</td>
                                        <td class="pl-4">#PAY-{{ $payment->id }}</td>
                                        <td> {{ $payment->payment_date }}</td>

                                        <td>#CUS-{{ $payment->user?->id }}</td>

                                        <td>{{ $payment->user?->full_name }}</td>
                                        <td>#INV-{{ $payment->invoice }}</td>
                                        <td>#WO-{{ $payment->workorder }}</td>
                                        <td>{{ array_sum(explode(',', $payment->paid_amount)) }}</td>
                                        <td>{{ $payment->payment_method }}</td>
                                        <td>{{ $payment->payment_reference }}</td>
                                        <td>
                                            @php
                                                $colorClass = $statusColors[$payment->status] ?? 'badge-success';
                                            @endphp
                                            <span class="badge {{ $colorClass }}"
                                                style="color:white;width:75px;">{{ ucfirst($payment->status) }}</span>
                                        </td>
                                        <td class="action-buttons">
                                            @can('show payment')
                                                <a class="text-success" href="{{ route('payment.show', $payment->id) }}">
                                                    <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                    <i data-feather="eye"></i>
                                                </a>
                                            @endcan
                                            <!-- @can('edit payment')
                                                <a class="text-success  px-2"
                                                    href="{{ route('workorder.payment', $payment->workorder) }}">
                                                    <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                    <i data-feather="edit"></i>
                                                </a>
                                            @endcan -->
                                            @can('delete payment')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['payment.destroy', $payment->id]]) !!}
                                                <a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Delete') }}" href="#">
                                                    <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                        style="width:25px;height:25px;">
                                                </a>
                                                {!! Form::close() !!}
                                            @endcan
                                            <a href="{{ route('pdf', $payment->id) }}"><i
                                                    class="text-success mr-2 icon-cloud-download"
                                                    style="font-size:25px;"></i></a>

                                            <a href="{{ route('sendemail', $payment->id) }}">

                                                <i class="fas fa-share mr-2 text-secondary" style="font-size:25px"></i></a>

                                        </td>
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
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
