@extends('layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('WorkOrders') }}</h4><span
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
                        <table class="table text-md-nowrap datatbl-advance">
                            <thead>
                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Service Group</th>
                                    <th>Phone</th>
									<th>City</th>
                                    <th>V Model</th>
                                    <th>Booking Schedule Date</th>
                                    <th>Agent</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workorders->sortByDesc('id')->values() as $key => $workorder)
                                    <tr>
                                        <td class="d-none" data-order="{{ $key + 1 }}">{{ $key + 1 }}</td>
                                        <td data-order="{{ $key + 1 }}">#WO-{{ $workorder->id }}</td>
                                        <td>{{ $workorder->client?->full_name }}</td>
                                        <td>{{ App\Models\ServiceGroups::where('id', json_decode($workorder->service_group, true))->first()?->name ?? '' }}
                                        </td>
                                        <td>+{{ preg_replace('/[^0-9]/', '', $workorder->client->ccm) }}{{ $workorder->client?->phone_number }}
                                        </td>
										<td>{{$workorder->bookings?->city}}</td>
                                        <td>{{ $workorder->vehicle()->vehicle_models->model_name ?? '' }} </td>

                                        <td>{{ App\Models\Booking::where('id', json_decode($workorder->booking, true))->first()?->booking_date ?? '' }}
                                        </td>
                                        <td>{{ $workorder->agent->first_name }}</td>
                                        <td>
                                            @if ($workorder->status == 'Open')
                                                <span class="badge" style="background-color: #1e90ff ;color:white;width:75px;">{{ $workorder->status }}</span>
                                            @elseif($workorder->status == 'Completed')
                                                <span class="badge"
                                                    style="background-color:green; color:white;width:75px;">{{ $workorder->status }}</span>
                                            @elseif($workorder->status == 'Cancelled' || $workorder->status == 'canceled' || $workorder->status == 'Cancel')
                                               
                                                @php
                                                    $booking = App\Models\Booking::whereIn(
                                                        'id',
                                                        json_decode($workorder->booking),
                                                    )->first();
                                                @endphp

                                                <button type="button"
                                                    style="color:white;width:75;"class="badge badge-primary border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal_{{ $workorder->id }}"
                                                    style="outline: none; box-shadow: none;">
                                                    CANCELLED
                                                </button>

                                                <div class="modal fade" id="exampleModal_{{ $workorder->id }}"
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
                                            @elseif($workorder->status == 'Accepted')
                                                <span class="badge"
                                                    style="background-color: #007bff; color: white;width:75px;">{{ $workorder->status }}</span>
                                            @elseif($workorder->status == 'Confirmed')
                                                <span class="badge"
                                                    style="background-color:#ff6347; color: white;width:75px;">{{ $workorder->status }}</span>
                                            @elseif($workorder->status == 'Pending')
                                                <span class="badge"
                                                    style="background-color: #ffc107; color: white;width:75px;">{{ $workorder->status }}</span>
                                            @elseif($workorder->status == 'Invoiced')
                                                <span class="badge badge-danger"
                                                    style="color:white;width:75px;">{{ $workorder->status }}</span>
                                            @elseif($workorder->status == 'OnHold')
                                                <span class="badge"
                                                    style="background-color:rgb(40, 131, 167); color: white;width:75px;">{{ $workorder->status }}</span>
                                            @elseif($workorder->status == 'Enroute')
                                                <span class="badge"
                                                    style="background-color:rgb(40, 131, 167); color: white;width:75px;">{{ $workorder->status }}</span>
                                            @elseif($workorder->status == 'Paid')
                                                <span class="badge badge-success"
                                                    style=" color:white;width:75px;">{{ $workorder->status }}</span>
                                            @else
                                                <span class="badge"
                                                    style="background-color: #4e89c4; color: white;width:75px;">{{ $workorder->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($workorder->status !== 'canceled')
                                                <div class="cart-action">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['workorder.destroy', $workorder->id]]) !!}
                                                    <a class="btn btn-sm btn-outline-warning" href="/workorder/details/{{ $workorder->id }}">
                                                        <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                            style='width:20px;height:20px;'> View
                                                    </a>
                                                
                                                <a class="btn btn-sm btn-outline-danger confirm_dialog" href="#">
                                                    <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                        style='width:25px;height:20px;'>Delete
                                                </a>
                                           {!! Form::close() !!}
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('No actions available') }}</span>
                                            @endif
                                        </td>

                                        {{-- <td class="pl-4">
                                            <a class="btn btn-outline-primary" href="{{ route('invoice.show', $invoice->id) }}">
                                                <img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
                                                    style="width:20px; height:20px;"> 
                                            </a> 
                                            
                                        </td> --}}


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
