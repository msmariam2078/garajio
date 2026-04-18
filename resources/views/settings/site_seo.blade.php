@extends('layouts.master')
@section('title','Site Seo Setting')
@php
    $settings=settings();

@endphp
@section('css')

    <!--Internal   Notify -->
    <link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css" integrity="sha384-NvKbDTEnL+A8F/AA5Tc5kmMLSJHUO868P+lDtTpJIeQdGYaUIuLr4lVGOEA1OcMy" crossorigin="anonymous">
	@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto"> {{__('General ')}}</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
						</div>
					</div>
				
         
                    
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
@include('messages_alert')
{{Form::model($settings, array('route' => array('setting.site.seo'), 'method' => 'post', 'enctype' => "multipart/form-data")) }}
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{Form::label('meta_seo_image',__('Meta Image'),array('class'=>'form-label'))}}
                            {{Form::file('meta_seo_image',array('class'=>'form-control'))}}
                        </div>
                    </div>
                    @if(!empty($settings['meta_seo_image']))
                        <div class="col-12 mt-20">
                            <img src="{{asset(Storage::url('upload/seo')).'/'.$settings['meta_seo_image']}}" class="setting-logo" alt="">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('meta_seo_title',__('Meta Title'),array('class'=>'form-label'))}}
                                {{Form::text('meta_seo_title',$settings['meta_seo_title'],array('class'=>'form-control','placeholder'=>__('Enter meta SEO title'),'required'=>'required'))}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('meta_seo_keyword',__('Meta Keyword'),array('class'=>'form-label'))}}
                                {{Form::text('meta_seo_keyword',$settings['meta_seo_keyword'],array('class'=>'form-control','placeholder'=>__('Enter meta SEO keyword'),'required'=>'required'))}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('meta_seo_description',__('Meta Description'),array('class'=>'form-label'))}}
                                {{ Form::textarea('meta_seo_description',$settings['meta_seo_description'], array('class' => 'form-control','placeholder'=>__('Enter meta SEO description'),'required'=>'required','rows'=>'2')) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        {{Form::submit(__('Save'),array('class'=>'btn btn-primary btn-rounded'))}}
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection
@section('js')
 
    <!--Internal  Notify js -->
    <script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
@endsection