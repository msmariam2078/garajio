@extends('layouts.master')
@section('title','Account Setting')
@php
    $settings = settings();

@endphp
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Company information') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body px-5">
                    {{ Form::model($settings, ['route' => ['setting.company'], 'method' => 'post']) }}
                    <div class="row">
                        <div class="form-group col-md-3">
                            {{ Form::label('company_name', __('Name'), ['class' => 'form-label']) }}
                            {{ Form::text('company_name', $settings['company_name'], ['class' => 'form-control', 'placeholder' => __('Enter company name')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('company_email', __('Email'), ['class' => 'form-label']) }}
                            {{ Form::text('company_email', $settings['company_email'], ['class' => 'form-control', 'placeholder' => __('Enter company email')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('company_phone', __('Phone Number'), ['class' => 'form-label']) }}
                            {{ Form::text('company_phone', $settings['company_phone'], ['class' => 'form-control', 'placeholder' => __('Enter company phone')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('company_website', __('Company website'), ['class' => 'form-label']) }}
                            {{ Form::text('company_website', $settings['company_website'], ['class' => 'form-control', 'placeholder' => __('Company website')]) }}
                        </div>
						<div class="form-group col-md-3">
                            {{ Form::label('company_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::text('company_address', $settings['company_address'], ['class' => 'form-control', 'placeholder' => __('Address')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('country_code', __('Country Code'), ['class' => 'form-label']) }}
                            {{ Form::select('country_code', $country_code, null, ['class' => 'form-control', 'id' => 'country_code']) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                            {{ Form::select('country', $country, null, ['class' => 'form-control', 'id' => 'country']) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('CURRENCY_SYMBOL', __('Currency Icon'), ['class' => 'form-label']) }}
                            {{ Form::text('CURRENCY_SYMBOL', $settings['CURRENCY_SYMBOL'], ['class' => 'form-control', 'placeholder' => __('Enter currency symbol')]) }}
                        </div>
                        <div class="col-md-3">
                            {{ Form::label('timezone', __('Timezone'), ['class' => 'form-label text-dark']) }}
                            <select name="timezone" class="form-control" id="timezone">
                                <option value="">{{ __('Select Timezone') }}</option>
                                @foreach ($timezones as $k => $timezone)
                                    <option value="{{ $timezone }}"
                                        {{ $settings['timezone'] == $timezone ? 'selected' : '' }}>
                                        {{ $timezone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            {{ Form::label('client_number_prefix', __('Client Number Prefix'), ['class' => 'form-label']) }}
                            {{ Form::text('client_number_prefix', $settings['client_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter client number prefix')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('estimation_number_prefix', __('Estimation Number Prefix'), ['class' => 'form-label']) }}
                            {{ Form::text('estimation_number_prefix', $settings['estimation_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter estimation number prefix')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('workorder_number_prefix', __('Workorder Number Prefix'), ['class' => 'form-label']) }}
                            {{ Form::text('workorder_number_prefix', $settings['workorder_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter workorder number prefix')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('invoice_number_prefix', __('Invoice Number Prefix'), ['class' => 'form-label']) }}
                            {{ Form::text('invoice_number_prefix', $settings['invoice_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter invoice number prefix')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('technician_number_prefix', __('Technitian Number Prefix'), ['class' => 'form-label']) }}
                            {{ Form::text('technician_number_prefix', $settings['technician_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter technician number prefix')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('booking_number_prefix', __('Inquiry Number Prefix'), ['class' => 'form-label']) }}
                            {{ Form::text('booking_number_prefix', $settings['booking_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter Inquiry number prefix')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('inquiry_number_prefix', __('Booking Number Prefix'), ['class' => 'form-label']) }}
                            {{ Form::text('inquiry_number_prefix', $settings['inquiry_number_prefix'], ['class' => 'form-control', 'placeholder' => __('Enter Inquiry number prefix')]) }}
                        </div>
                        <div class="form-group col-md-3">
                            {{ Form::label('google_api_key', __('Google Api Key'), ['class' => 'form-label']) }}
                            {{ Form::text('google_api_key', $settings['google_api_key'], ['class' => 'form-control', 'placeholder' => __('Enter Google Api Key')]) }}
                        </div>

                        <div class="form-group col-md-3">
                            {{ Form::label('taxname', __('Tax Name'), ['class' => 'form-label']) }}
                            {{ Form::text('taxname', $settings['taxname'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter tax name')]) }}
                        </div>

						<div class="form-group col-md-3">
                            {{ Form::label('trn_no', __('TRN No'), ['class' => 'form-label']) }}
                            {{ Form::text('trn_no', $settings['trn_no'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter trn no')]) }}
                        </div>

                        <div class="form-group col-md-3">
                            {{ Form::label('purchasetaxrate', __('Purchase Tax Rate (%)'), ['class' => 'form-label']) }}
                            {{ Form::number('purchasetaxrate', $settings['purchasetaxrate'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter purchase tax rate')]) }}
                        </div>

                        <div class="form-group col-md-3">
                            {{ Form::label('salestaxrate', __('Sales Tax Rate (%)'), ['class' => 'form-label']) }}
                            {{ Form::number('salestaxrate', $settings['salestaxrate'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter sales tax rate')]) }}
                        </div>

                        <div class="form-group col-md-3" id="otherTaxNameField">
                            {{ Form::label('taxname', __('Other Tax Name'), ['class' => 'form-label']) }}
                            {{ Form::text('othertaxname', $settings['othertaxname'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter other tax name')]) }}
                        </div>

                        <div class="col-md-3 mt-4">
                            <div class="p-2 border">
                                <div class="form-check">
                                    {{ Form::checkbox('prices_include_tax', 1, $settings['prices_include_tax'] ?? false, ['class' => 'form-check-input', 'id' => 'pricesIncludeTax']) }}
                                    {{ Form::label('pricesIncludeTax', __('Prices Include Tax'), ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mt-4">
                            <div class="p-2 border">
                                <div class="form-check">
                                    {{ Form::checkbox('other_taxes', 1, $settings['other_taxes'] ?? false, ['class' => 'form-check-input', 'id' => 'othertaxes']) }}
                                    {{ Form::label('taxFreight', __('Other Taxes'), ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mt-4">
                            <div class="p-2 border">
                                <div class="form-check">
                                    {{ Form::checkbox('round_total', 1, $settings['round_total'] ?? false, ['class' => 'form-check-input', 'id' => 'roundTotal']) }}
                                    {{ Form::label('roundTotal', __('Round Total'), ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mt-4">
                            <div class="p-2 border">
                                <div class="form-check">
                                    {{ Form::checkbox('discount_include_tax', 1, $settings['discount_include_tax'] ?? false, ['class' => 'form-check-input', 'id' => 'discountIncludeTax']) }}
                                    {{ Form::label('discountIncludeTax', __('Discount Includes Tax'), ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            {{ Form::label('company_zipcode', __('System Date Format'), ['class' => 'form-label']) }}
                            <div class="d-flex border p-2">
                                <div class="custom-control custom-radio ml-2">
                                    <input type=" radio" id="company_date_format1" name="company_date_format"
                                        class="custom-control-input" value="M j, Y"
                                        {{ $settings['company_date_format'] == 'M j, Y' ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="company_date_format1">{{ date('M d,Y') }}</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input type="radio" id="company_date_format2" name="company_date_format"
                                        class="custom-control-input" value="y-m-d"
                                        {{ $settings['company_date_format'] == 'y-m-d' ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="company_date_format2">{{ date('y-m-d') }}</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input type=" radio" id="company_date_format3" name="company_date_format"
                                        class="custom-control-input" value="d-m-y"
                                        {{ $settings['company_date_format'] == 'd-m-y' ? 'checked' : '' }}>
                                    <label class="custom-control-label ml-2""
                                        for=" company_date_format3">{{ date('d-m-y') }}</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input type=" radio" id="company_date_format4" name="company_date_format"
                                        class="custom-control-input" value="m-d-y"
                                        {{ $settings['company_date_format'] == 'm-d-y' ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="company_date_format4">{{ date('m-d-y') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            {{ Form::label('company_zipcode', __('System Time Format'), ['class' => 'form-label']) }}
                            <div class="d-flex border p-2">
                                <div class="custom-control custom-radio ml-2">
                                    <input type=" radio" id="company_time_format1" name="company_time_format"
                                        class="custom-control-input" value="H:i"
                                        {{ $settings['company_time_format'] == 'H:i' ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="company_time_format1">{{ date('H:i') }}</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input type=" radio" id="company_time_format2" name="company_time_format"
                                        class="custom-control-input" value="g:i A"
                                        {{ $settings['company_time_format'] == 'g:i A' ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="company_time_format2">{{ date('g:i A') }}</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input type=" radio" id="company_time_format3" name="company_time_format"
                                        class="custom-control-input" value="g:i a"
                                        {{ $settings['company_time_format'] == 'g:i a' ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="company_time_format3">{{ date('g:i a') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const otherTaxesCheckbox = document.getElementById('othertaxes');
                            const otherTaxNameField = document.getElementById('otherTaxNameField');

                            // Show or hide the "Other Tax Name" field based on the checkbox state
                            if (otherTaxesCheckbox.checked) {
                                otherTaxNameField.style.display = 'block';
                            } else {
                                otherTaxNameField.style.display = 'none';
                            }

                            // Add event listener to toggle visibility when checkbox state changes
                            otherTaxesCheckbox.addEventListener('change', function() {
                                if (this.checked) {
                                    otherTaxNameField.style.display = 'block';
                                } else {
                                    otherTaxNameField.style.display = 'none';
                                }
                            });
                        });
                    </script>

                    <div class="row justify-content-center mt-4">
                        {{ Form::submit(__('Save now'), ['class' => 'btn btn-primary col-md-3']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    </div>
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
