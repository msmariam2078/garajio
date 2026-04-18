@extends('layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">
                    <a href="{{ route('payment.technicianindex') }}" class="text-dark">Payment</a>
                </h4>
                <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
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
                <form method="GET" action="{{ url('payment-search') }}" class="{{ Auth::user()->type == 'technician' ? 'd-none' : '' }}">
                    <div class="d-flex justify-content-center align-items-center mt-4"
                        style="position: absolute; left: 300px; top:-80px; z-index: 1000; gap: 1rem;">
                        <div class="d-flex align-items-center">
                            <label for="from_date" class="m-2 mb-0"><strong>From</strong></label>
                            <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}"
                                class="form-control">
                        </div>
                        <div class="d-flex align-items-center">
                            <label for="to_date" class="m-2 mb-0"><strong>To</strong></label>
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
                                    <th>{{ __('Payment ID') }}</th>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Invoice ID') }}</th>
                                    <th>{{ __('Workorder ID') }}</th>
                                    <th>{{ __('Customer ID') }}</th>
                                    <th>{{ __('Customer Name') }}</th>
                                    <th>{{ __('Customer Phone') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Payment Amount') }}</th>
                                    <th>{{ __('Payment Ref No') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments->sortByDesc('id')->values() as $key => $payment)
                                    <tr>
                                        <td class="d-none" data-order="{{ $key + 1 }}">{{ $key + 1 }}</td>
                                        <td>#PAY-{{ $payment->id }}</td>
                                        <td>{{ $payment->payment_date }}</td>
                                        <td>#INV-{{ $payment->invoice }}</td>
										<td>#WO-{{ $payment->workorder }}</td>
                                        <td>
                                            #CLI-{{$payment->client }}

                                            {{-- <a href="{{ route('client.show', \Illuminate\Support\Facades\Crypt::encrypt($payment->client)) }}"
                                                target="_blank">
                                                #CLI-{{$payment->client }}
                                            </a> --}}
                                        </td>
                                        <td>{{ $payment->user->full_name }}</td>
                                		<td>+{{preg_replace('/[^0-9]/', '',  $payment->user->ccm )}}{{$payment->user->phone_number}}
                                        <td>{{ $payment->payment_method }}</td>
										<td>{{ number_format((float) $payment->paid_amount, 2) }}</td>
                                        <td>{{ $payment->payment_reference }}</td>
										 <td>
                                        	<span class="badge badge-success px-4">{{ $payment->status }}</span>
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
