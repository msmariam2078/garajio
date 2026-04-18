@extends('layouts.master')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Invoice')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">
            <div class="btn-group dropdown">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#invoiceModal">Create
                    Invoice</button>

            </div>
        </div>
    </div>
 
</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('messages_alert')
<!-- row opened -->
<div class="row row-sm">

    <div class="col-xl-12">
        <div class="card">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap datatbl-advance">
                        <thead>

                            <tr>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Invoice Date')}}</th>
                                <th>{{__('Customer')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Phone')}}</th>
                                <th>{{__('V.Reg')}}</th>
                                <th>{{__('Workorder ')}}</th>
                                <th>{{__('Technician ')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Total')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $key=>$invoice)
                            <tr>
                                <td data-order="{{ $key+1}}">{{invoicePrefix()}}{{ $key+1 }}</td>
                                <td>{{ $invoice->invoice_date }}</td>
                                <td>{{ $invoice->clients->first_name }}</td>
                                <td>{{ $invoice->clients->type }}</td>
                                <td>{{ $invoice->clients->phone_number }}</td>
                                <td>Dummy V.Reg</td>
                                <td>{{ workOrderPrefix().$invoice->wo_id }}</td>
                                <td>{{ technicianPrefix().$invoice->wo_id}}</td>
                                <td>{{ $invoice->status }}</td>
                                <td>{{ $invoice->final_amount }}</td>
                               





                            </tr>
                           
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- bd -->
            </div><!-- bd -->
        </div><!-- bd -->
    </div>
    <!--/div-->




</div>
<!-- /row -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->

@endsection
@section('js')



<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>

<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>


<script>
$(function() {
    $("#client_invoice").selectize();
});
</script>
@endsection