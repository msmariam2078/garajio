@extends('layouts.master')
@section('title', 'Invoice')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Invoice') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    @can('create invoice')
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#invoiceModal">
                            <i class="ti-plus mr-1"></i>
                            Create Invoice
                        </button>
                    @endcan
                </div>
            </div>
        </div>
        @include('invoice.create')
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

                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Invoice Date') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Mobile') }}</th>

                                    <th>{{ __('Workorder ') }}</th>
                                    <th>{{ __('Technician ') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Total') }}</th>
                                    <th>{{ __('Action') }}</th>
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

                                @foreach ($invoices->sortByDesc('id')->values() as $key => $invoice)
                                    <tr>
                                        <td class="d-none" data-order="{{ $key + 1 }}">
                                            {{ invoicePrefix() }}{{ $key + 1 }}</td>
                                        {{-- <td class="text-center">{{ invoicePrefix() }}{{ $key + 1 }}</td> --}}
                                        <td class="text-center">#INV-{{ $invoice->id }}</td>
                                        <td>{{ $invoice->invoice_date }}</td>
                                        <td>Cus-{{ $invoice->client }}</td>
                                        <td>{{ $invoice->clients?->first_name }} {{ $invoice->clients?->last_name }}</td>

                                        <td>+{{ preg_replace('/[^0-9]/', '', $invoice->clients?->ccm) . $invoice->clients?->phone_number }}
                                        </td>
                                        <td>#WO-{{ $invoice->wo_id }}</td>
                                        <td>{{ App\Models\User::where('id', json_decode($invoice->workorders?->technician, true))->first()?->full_name ?? '' }}
                                        </td>
                                        <td>
                                            @php
                                                $colorClass =
                                                    $statusColors[strtolower($invoice->status)] ?? 'badge-dark';
                                            @endphp
                                            <span class="badge {{ $colorClass }}"
                                                style="color:white;width:75px;">{{ ucfirst($invoice->status) }}</span>
                                        </td>
                                        <td>{{ $invoice->total }}</td>
                                        <td class="btn-group">
                                            <!-- <a class="px-2" href="{{ url('bookinginvoice/' . $invoice->wo_id) }}" >
                                                    <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a> -->

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id]]) !!}
                                            @can('show invoice')
                                                <a class="text-warning" href="{{ route('invoice.show', $invoice->id) }}">
                                                    <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                        style="width:25px;height:25px;">
                                                </a>
                                            @endcan
                                            @can('delete invoice')
                                                <a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Delete') }}" href="#">
                                                    <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                            @endcan
                                            {!! Form::close() !!}
                                        </td>
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


    <script>
        $(function() {
            $("#client_invoice").selectize();
        });
    </script>
@endsection
