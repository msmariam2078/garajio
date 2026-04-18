@extends('layouts.master')
@section('title','General Setting')
@php
$settings=settings();

@endphp
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />

<link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css" />

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
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                {{Form::model($settings, array('route' => array('setting.general'), 'method' => 'post', 'enctype' => "multipart/form-data")) }}
                <div class="row">

                    <div class="col-md-12 mb-4">
                        <div class="form-group">
                            {{Form::label('application_name',__('Application Name'),array('class'=>'form-label mb-3'))}}
                            {{Form::text('application_name',!empty($settings['app_name'])?$settings['app_name']:env('APP_NAME'),array('class'=>'form-control','placeholder'=>__('Enter your application name'),'required'=>'required'))}}
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-4">
                        <div class="form-group">
                            {{Form::label('logo',__('Logo'),array('class'=>'form-label mb-3'))}}
                            <input type="file" name='logo' class="dropify" data-height="100" />

                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-4">
                        {{Form::label('favicon',__('Favicon'),array('class'=>'form-label mb-3'))}}
                        <input type="file" name='favicon' class="dropify" data-height="100" />
                    </div>

                    @if(\Auth::user()->type=='super admin')
                    <div class="col-md-6">
                        <div class="form-group">
                            {{Form::label('landing_logo',__('Landing Page Logo'),array('class'=>'form-label'))}}
                            {{Form::file('landing_logo',array('class'=>'form-control'))}}
                        </div>
                    </div>

                    @endif
                </div>
                <div class="text-right">
                    {{Form::submit(__('Save'),array('class'=>'btn btn-primary btn-rounded'))}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>
@endsection