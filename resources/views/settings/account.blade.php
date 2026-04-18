@extends('layouts.master')
@section('title','Account Setting')
@php
$settings=settings();

@endphp
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css"
    integrity="sha384-NvKbDTEnL+A8F/AA5Tc5kmMLSJHUO868P+lDtTpJIeQdGYaUIuLr4lVGOEA1OcMy" crossorigin="anonymous">
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
    <div class="col-xl-12 cdx-xxl-100 cdx-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="info-group">
                    {{Form::model($loginUser, array('route' => array('setting.account'), 'method' => 'post', 'enctype' => "multipart/form-data")) }}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                {{Form::label('name',__('Name'),array('class'=>'form-label mb-3'))}}
                                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter your name'),'required'=>'required'))}}
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                {{Form::label('email',__('Email Address'),array('class'=>'form-label mb-3'))}}
                                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter your email'),'required'=>'required'))}}
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="form-group">
                                {{Form::label('profile',__('Profile'),array('class'=>'form-label mb-3'))}}
                                {{Form::file('profile',array('class'=>'form-control'))}}
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
<script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>
@endsection