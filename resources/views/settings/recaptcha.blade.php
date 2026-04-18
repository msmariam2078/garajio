@extends('layouts.master')
@section('title','ReCaptch Setting')
@section('css')
	<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />	
@endsection

@php
    $settings = settings();
@endphp

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('General ') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($settings, ['route' => ['setting.google.recaptcha'], 'method' => 'post']) }}
                    <div class="row mt-2">
                        <div class="col-auto">
                            {{ Form::label('google_recaptcha', __('Google ReCaptch Enable'), ['class' => 'form-label']) }}
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check custom-chek">
                                    <input class="form-check-input" type="checkbox" name="google_recaptcha"
                                        id="google_recaptch" {{ $settings['google_recaptcha'] == 'on' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            {{ Form::label('recaptcha_key', __('Recaptcha Key'), ['class' => 'form-label']) }}
                            {{ Form::text('recaptcha_key', $settings['recaptcha_key'], ['class' => 'form-control', 'placeholder' => __('Enter recaptcha key')]) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('recaptcha_secret', __('Recaptcha Secret'), ['class' => 'form-label']) }}
                            {{ Form::text('recaptcha_secret', $settings['recaptcha_secret'], ['class' => 'form-control ', 'placeholder' => __('Enter recaptcha secret')]) }}
                        </div>
                    </div>

                    <div class="text-right">
                        {{ Form::submit(__('Save'), ['class' => 'btn btn-primary btn-rounded']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection