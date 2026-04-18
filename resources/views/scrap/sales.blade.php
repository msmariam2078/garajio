@extends('layouts.master')
@section('title','Module Setup')
@php
    $settings = settings();
@endphp
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet"/>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Module Setup') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/Sales Module</span>
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
                    <form action="{{ route('sales.invoices.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
						<input type="hidden" name="id" value="{{$salesModule?->id}}">
                        <div class="row justify-content-center">
                            <div class="form-group col-md-4">
                                <label for="prefix" class="form-label">Invoice Number Prefix</label>
                                <input type="text" name="prefix" class="form-control" value="INV">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="invoice_number" class="form-label">Next Invoice ID</label>
                                <input type="text" name="invoice_number" class="form-control bg-transparent"
                                    value="{{ $nextInvoiceNumber }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="invoice_page">Invoice Page Setup</label>
                                <select class="form-control" name="invoice_page">
                                    <option value="A4">A4</option>
                                    <option value="Custom">Custom</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="invoice_name" class="form-label">Invoice Name</label>
                                <input type="text" name="invoice_name" class="form-control" value="{{$salesModule?->invoice_name}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="invoice_arabic_name" class="form-label">Invoice Arabic Name</label>
                                <input type="text" name="invoice_arabic_name" class="form-control" value="{{$salesModule?->invoice_arabic_name}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="invoice_logo" class="form-label">Invoice Logo (Image Upload)</label>
                                <input type="file" name="invoice_logo" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="terms" class="form-label">Invoice: Term and Conditions</label>
                                <textarea id="summernote" name="terms" cols="10" rows="5" class="form-control">
									{{$salesModule?->terms}}
								</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary col-md-4">Update Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    </script>
@endsection