@extends('layouts.master')
@section('title','Password Setting')
@php
$settings=settings();

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
            <h4 class="content-title mb-0 my-auto"> {{__('General ')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>



</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('messages_alert')
<div class="row">
    <div class="col-xl-12 cdx-xxl-100 cdx-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="info-group">
                    {{Form::model($loginUser, array('route' => array('setting.password'), 'method' => 'post')) }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('current_password',__('Current Password'),array('class'=>'form-label'))}}
                                {{Form::password('current_password',array('class'=>'form-control','placeholder'=>__('Enter your current password'),'required'=>'required'))}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('new_password',__('New Password'),array('class'=>'form-label'))}}
                                {{Form::password('new_password',array('class'=>'form-control','placeholder'=>__('Enter your new password'),'required'=>'required'))}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('confirm_password',__('Confirm New Password'),array('class'=>'form-label'))}}
                                {{Form::password('confirm_password',array('class'=>'form-control','placeholder'=>__('Enter your confirm new password'),'required'=>'required'))}}
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        {{Form::submit(__('Save'),array('class'=>'btn btn-primary btn-rounded'))}}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
@endsection