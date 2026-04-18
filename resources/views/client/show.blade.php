@extends('layouts.master')
@section('css')




@endsection
@section('page-header')

<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Client')}}</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                <a href="#">
                    {{clientPrefix()}}{{!empty($client->clients)?$client->clients?->client_id:''}} {{__('Details')}}
                </a></span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        
        <a href="{{ route('client.index) }}" class="btn btn-primary ml-2">
            <i class="fas fa-arrow-left"></i> {{__('Back')}}
        </a>
    </div>
</div>

@endsection
@section('content')
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Name')}}</h6>
                            <p class="mb-20">{{$client->first_name}}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Email')}}</h6>
                            <p class="mb-20">{{$client->email}}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Phone Number')}}</h6>
                            <p class="mb-20">{{$client->phone_number}}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Company')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->company:'-'}} </p>
                        </div>
                    </div>
                    <!--<div class="col-md-4 col-lg-4">-->
                    <!--    <div class="detail-group">-->
                    <!--        <h6 class='font-weight-bold'>{{__('Credit Limit')}}</h6>-->
                    <!--        <p class="mb-20">{{$client->credit_limit ?? '-'}} </p>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Payment Terms Code')}}</h6>
                            <p class="mb-20">{{ $client->payment_terms_code ?? '-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Payment Method Code')}}</h6>
                            <p class="mb-20">{{ $client->payment_method_code ?? '-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Vat Bus Posting Group')}}</h6>
                            <p class="mb-20">{{ $client->vat_bus_posting_group ?? '-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Customer Posting Group')}}</h6>
                            <p class="mb-20">{{ $client->customer_posting_group ?? '-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Gen Bus Posting Group')}}</h6>
                            <p class="mb-20">{{ $client->gen_bus_posting_group ?? '-'}} </p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class=" col-md-12 mb-20">
                    <h5> {{__('Service Address')}}</h5>
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Country')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->service_country:'-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('State')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->service_state:'-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('City')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->service_city:'-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Zip Code')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->service_zip_code:'-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Address')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->service_address:'-'}} </p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class=" col-md-12 mb-20">
                    <h5> {{__('Billing Address')}}</h5>
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Billing Country')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->billing_country:'-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Billing State')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->billing_state:'-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Billing City')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->billing_city:'-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Billing Zip Code')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->billing_zip_code:'-'}} </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Billing Address')}}</h6>
                            <p class="mb-20">{{!empty($client->clients)?$client->clients->billing_address:'-'}} </p>
                        </div>
                    </div>
                </div>
                <hr>
                @if ($client->clients?->addresses != null)
                <div class=" col-md-12 mb-20">
                    <h5> {{__('Address')}}</h5>
                </div>
                <div class="row">

                    @php
                    $addresses = json_decode($client->clients?->addresses, true);
                    @endphp
                    @for ($i = 1; $i < count($addresses) / 5; $i++) <div class="col-md-12">
                        <div class="detail-group">
                            <h6 class='font-weight-bold'>{{__('Address')}} {{ $i }}</h6>
                            <p class="mb-20">{{ $addresses[$i * 5]['address'] ?? '-' }}</p>
                        </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="detail-group">
                        <h6 class='font-weight-bold'>{{__('City')}}</h6>
                        <p class="mb-20">{{ $addresses[$i * 5 + 1]['city'] ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="detail-group">
                        <h6 class='font-weight-bold'>{{__('State')}}</h6>
                        <p class="mb-20">{{ $addresses[$i * 5 + 2]['state'] ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="detail-group">
                        <h6 class='font-weight-bold'>{{__('Country')}}</h6>
                        <p class="mb-20">{{ $addresses[$i * 5 + 3]['country'] ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4">
                    <div class="detail-group">
                        <h6 class='font-weight-bold'>{{__('Zip/Postal Code')}}</h6>
                        <p class="mb-20">{{ $addresses[$i * 5 + 4]['zip_code'] ?? '-' }}</p>
                    </div>
                </div>
                <hr>
                @endfor

            </div>
            @else
            <div class="col-md-12">
                <p>No addresses available</p>
            </div>
            @endif
        </div>
    </div>

</div>
</div>

@endsection
@section('js')




@endsection