@extends('layouts.master')
@section('title', 'Booking')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Booking</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                @if (Gate::check('create booking'))
                    <a class="btn btn-primary ml-20 " href="{{ route('booking.create') }}" data-size="lg"> <i
                            class="ti-plus mr-1"></i>
                        {{ __('Create Booking') }}
                @endif </a>

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
                        <table class="table text-md-nowrap datatbl-advance" style="font-size:13px">

                            <thead>
                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th class="auto border-bottom-0">{{ __('Booking-ID') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Requested Date') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Requested Time') }}</th>
                                    <!-- <th class="wd-15p border-bottom-0">{{ __('Service Group') }}</th> -->
                                    <th class="wd-15p border-bottom-0">{{ __('Skill Group') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Customer Name') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Customer Phone') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Vehicle Registration No') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Status') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Agent') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody style="fony-weight: normal !important;">
                                @foreach ($bookings->sortByDesc('id')->values() as $key => $booking)
                                    <tr>
                                        <td class="d-none" data-order="{{ $key + 1 }}">{{ $key + 1 }}</td>
                                        <td lass="text-center">#BO-{{ $booking->id }}</td>
                                        <td>{{ $booking->requested_date }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->requested_time)->format('h:i A') }}</td>
                                        <td>{{ $booking->skill_group_names }}</td>
                                        <td> {{ $booking->user?->first_name }} {{ $booking->user?->last_name }}</td>
                                        <td>+{{ preg_replace('/[^0-9]/', '', $booking->user?->ccm) . $booking->user?->phone_number }}
                                        </td>
                                        <td> {{ @$booking->vehicless?->name }} {{ @$booking->vehicless?->rego }}
                                        </td>

                                        <td>
                                            @if ($booking->status == 'Booking')
                                                <span class="badge badge-info"
                                                    style="color:white;width:75px;">{{ $booking->status }}</span>
                                            @elseif($booking->status == 'Workorder')
                                                <span class=" badge badge-success"
                                                    style="color:white;width:75px;">{{ $booking->status }}</span>
                                            @elseif($booking->status == 'Quotation')
                                                <span class="badge badge-warning"
                                                    style="color:white;width:75px;">{{ $booking->status }}</span>
                                                     @elseif($booking->status == 'Inspection')
                                                <span class="badge badge-secondary"
                                                    style="color:white;width:75px;">{{ $booking->status }}</span>
                                            @elseif($booking->status == 'canceled')
                                                <button type="button" class="badge badge-primary border-0"
                                                    style="width:75px;"data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal_{{ $booking->id }}"
                                                    style="outline: none; box-shadow: none;">
                                                    CANCELLED
                                                </button>

                                                <div class="modal fade" id="exampleModal_{{ $booking->id }}"
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
                                            @else
                                                <span class="badge badge-light">{{ $booking->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $booking->agent->first_name }}</td>
                                        <td>

                                            @if ($booking->status !== 'canceled')
                                                <div class="cart-action">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['booking.destroy', $booking->id]]) !!}

													@can('view booking')
														<a class="text-primary " data-bs-toggle="tooltip"
															data-bs-original-title="{{ __('View') }}"
															href="{{ route('booking.show', $booking->id) }}"
															data-title="{{ __('View Booking') }}">
															<img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
																style='width:25px;height:25px;'>
														</a>
                                                    @endcan
													
                                                    @can('edit booking')
                                                        <a class="text-success "
                                                            href="{{ $booking->status=='Inspection' || $booking->status=='Workorder' ? url('/inspectionbooking/'.$booking->id) : route('booking.edit', $booking->id) }}">
                                                            <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                                style='width:25px;height:25px;'>
                                                        </a>
                                                    @endcan
                                                    @can('delete booking')
                                                        <a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Delete') }}" href="#">
                                                            <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                                style='width:25px;height:25px;'>
                                                        </a>
                                                    @endcan


                                                    {!! Form::close() !!}
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('No actions available') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
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
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-script.js') }}"></script>
@endsection
<script>
    function initAutocomplete() {

    }
    $('#customModal').on('show.bs.modal', function() {
        initAutocomplete();
        // Initialize map and autocomplete on page load
        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    });
</script>
