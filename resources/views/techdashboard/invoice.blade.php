@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">
                    <a href="{{ route('invoice.technicianindex') }}" class="text-dark">Invoice</a>
                </h4>
                <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <form method="GET" action="{{ url('invoice-search') }}" class="{{ Auth::user()->type == 'technician' ? 'd-none' : '' }}">
                    <div class="d-flex justify-content-center align-items-center mt-4"
                        style="position: absolute; left: 300px; top:-80px; z-index: 1000; gap: 1rem;">
                        <div class="d-flex align-items-center">
                            <label for="from_date" class="m-2 mb-0">
								<strong>From</strong>
							</label>
                            <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}"
                                class="form-control">
                        </div>
                        <div class="d-flex align-items-center">
                            <label for="to_date" class="m-2 mb-0">
								<strong>To</strong>
							</label>
                            <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}"
                                class="form-control">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap datatbl-advance">
                            <thead>
                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th>{{ __('Invoice id') }}</th>
                                    <th>{{ __('Invoice Date') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('V.Reg') }}</th>
                                    <th>{{ __('Workorder id') }}</th>
                                    <th>{{ __('Technician ') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Total') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices->sortByDesc('id')->values() as $key => $invoice)
                                    <tr>
                                        <td class="d-none" data-order="{{ $key + 1 }}">{{ $key + 1 }}</td>
                                        <td>#INV-{{ $invoice->id }}</td>
                                        <td>{{ $invoice->invoice_date }}</td>
                                        <td>{{ $invoice->clients?->full_name }}</td>
                                        <td>{{ $invoice->clients?->type }}</td>
                                		<td>+{{preg_replace('/[^0-9]/', '',  $invoice->clients?->ccm )}}{{$invoice->clients?->phone_number}}
                                        <td>
											{{ $invoice->workorders->vehicle()->rego ?? '' }}
										</td>
                                        <td>#WO-{{$invoice->wo_id }}</td>
                                        <td>{{ App\Models\User::where('id', json_decode($invoice->workorders?->technician, true))->first()?->full_name ?? '' }}
                                        </td>

										<td>
                                            @if ($invoice->status == 'Open')
                                                <span class="badge" style="background-color: #1e90ff ;color:white;width:75px;">{{ $invoice->status }}</span>
                                            @elseif($invoice->status == 'Completed')
                                                <span class="badge"
                                                    style="background-color:green; color:white;width:75px;">{{ $invoice->status }}</span>
                                            @elseif($invoice->status == 'Cancelled' || $invoice->status == 'canceled' || $invoice->status == 'Cancel')
                                               
                                                @php
                                                    $booking = App\Models\Booking::whereIn(
                                                        'id',
                                                        json_decode($invoice->booking),
                                                    )->first();
                                                @endphp

                                                <button type="button"
                                                    style="color:white;width:75;"class="badge badge-primary border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal_{{ $invoice->id }}"
                                                    style="outline: none; box-shadow: none;">
                                                    CANCELLED
                                                </button>

                                                <div class="modal fade" id="exampleModal_{{ $invoice->id }}"
                                                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header py-2">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                                                                <button type="button" class="btn-close border"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close">&times;</button>
                                                            </div>
                                                            <div class="modal-body"
                                                                style="text-transform:capitalize !important;">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Reason for
                                                                        Cancellation</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $booking->reasons }}" readonly>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Cancellation Note</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $booking->notes }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer p-2">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($invoice->status == 'Accepted')
                                                <span class="badge"
                                                    style="background-color: #007bff; color: white;width:75px;">{{ $invoice->status }}</span>
                                            @elseif($invoice->status == 'Confirmed')
                                                <span class="badge"
                                                    style="background-color:#ff6347; color: white;width:75px;">{{ $invoice->status }}</span>
                                            @elseif($invoice->status == 'Pending')
                                                <span class="badge"
                                                    style="background-color: #ffc107; color: white;width:75px;">{{ $invoice->status }}</span>
                                            @elseif($invoice->status == 'Invoiced')
                                                <span class="badge badge-danger"
                                                    style="color:white;width:75px;">{{ $invoice->status }}</span>
                                            @elseif($invoice->status == 'OnHold')
                                                <span class="badge"
                                                    style="background-color:rgb(40, 131, 167); color: white;width:75px;">{{ $invoice->status }}</span>
                                            @elseif($invoice->status == 'Enroute')
                                                <span class="badge"
                                                    style="background-color:rgb(40, 131, 167); color: white;width:75px;">{{ $invoice->status }}</span>
                                            @elseif($invoice->status == 'Paid')
                                                <span class="badge badge-success"
                                                    style=" color:white;width:75px;">{{ $invoice->status }}</span>
                                            @else
                                                <span class="badge"
                                                    style="background-color: #4e89c4; color: white;width:75px;">{{ $invoice->status }}</span>
                                            @endif
                                        </td>


                                        <td>{{ number_format($invoice->final_amount, 2) }}</td>
                                        <td class="pl-4">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('invoice.show', $invoice->id) }}">
                                                <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                    style="width:20px; height:20px;"> View
                                            </a> 
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
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
