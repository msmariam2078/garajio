@extends('layouts.master')

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Estimation ')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Create</span>
        </div>
    </div>



</div>
<!-- breadcrumb -->
@endsection
@section('!content')
@include('messages_alert')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                   Estimation
                </div>

            </div>
            <div class="card-body">
                <div class="info-group row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        {{ Form::label('estimation_id', __('Estimation Number'), ['class' => 'form-label']) }}
                        <span class="text-danger">*</span>
                        <div class="input-group">
                            <span class="input-group-text">{{ estimationPrefix() }}</span>
                            {{ Form::text('estimation_id', $estimationNumber, ['class' => 'form-control', 'id' => 'estimation_id', 'placeholder' => __('Enter Estimation Number')]) }}
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        {{ Form::label('quotation_date', __('Quotation Date'), ['class' => 'form-label']) }}
                        <span class="text-danger">*</span>
                        {{ Form::date('quotation_date', null, ['class' => 'form-control', 'id' => 'quotation_date', 'required' => 'required']) }}
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
                        <span class="text-danger">*</span>
                        {{ Form::text('title', null, ['class' => 'form-control', 'id' => 'title', 'placeholder' => __('Enter title'), 'required' => 'required']) }}
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select id="customer_id" name="customer_id" class="form-control customer_id" required>
                                <option value="" disabled selected>Select Customer</option>
                                @foreach($clients as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="booking_id">Booking ID</label>
                            <input type="text" id="booking_id" name="booking_id" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}
                        <span class="text-danger">*</span>
                        {{ Form::date('due_date', null, ['class' => 'form-control', 'id' => 'due_date', 'required' => 'required']) }}
                    </div>
                    <div class="col-lg-6  col-sm-6 col-md-6">
                        {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
                        {{ Form::textarea('notes', null, ['class' => 'form-control', 'id' => 'notes', 'rows' => 2]) }}
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="customer_id">Status</label>
                            <select id="staus" name="status" class="form-control" required>
                                <option value="New">New</option>
                                <option value="Pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="accepted">Accepted</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
@section('js')


@endsection