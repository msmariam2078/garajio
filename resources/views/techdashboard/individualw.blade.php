@extends('layouts.master')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css"
    integrity="sha384-NvKbDTEnL+A8F/AA5Tc5kmMLSJHUO868P+lDtTpJIeQdGYaUIuLr4lVGOEA1OcMy" crossorigin="anonymous">

    <link href="{{URL::asset('assets/css/template.css')}}" rel="stylesheet" />


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Work Order')}}</h4>
        </div>
    </div>
   

</div>
<!-- breadcrumb -->
@endsection
@section('content')
<div class="card">
            <div class="title">Work Order {{$workOrder->id}}</div>
            <div class="info">
                <div class="row">
                    <div class="col-7">
                        <span id="heading">Date</span><br>
                        <span id="details">{{$workOrder->created_date}}</span>
                    </div>
                    <div class="col-5 pull-right">
                        <span id="heading">Customer name</span><br>
                        <span id="details">{{$workOrder->client?->full_name}}</span>
                    </div>
                </div>      
            </div>      
            <div class="pricing">
                <div class="row">
                    <div class="col-9">
                        <span id="name">Service Group</span>  
                    </div>
                    <div class="col-3">
                        <span id="price">{{$servicegroup[0]->name ?? "" }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9">
                        <span id="name">Service Location</span>
                    </div>
                    <div class="col-3">
                        <span id="price">{{$workOrder->service_location}}</span>
                    </div>
                </div>
            </div>
            <div class="total">
                <div class="row">
                    <div class="col-9">Equipment</div>
                    <div class="col-3"><big>{{$vehicles[0]->rego ?? ""}}</big></div>
                </div>
            </div>
            <div class="tracking">
                <div class="title">Tracking     Work Order</div>
            </div>
            <div class="progress-track">
                <ul id="progressbar">
                    <li class="step0 active " id="step1">confirmed</li>
                    @if($workOrder->status=="Enroute")
             

                    <li class="step0 active text-center" id="step2">Enroute</li>
                    @endif
                    @if($workOrder->status=="Startwork")
                    <li class="step0 active text-right" id="step3">Start Work</li>
                    @endif
                    @if($workOrder->status=="Completed")
                    <li class="step0 text-right" id="step4">Completed</li>
                    @endif
                </ul>
            </div>
            

            <div class="footer">
                
              
                
               
            </div>
        </div>
@endsection
@section('js')



<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>

<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>



@endsection