@extends('layouts.master')
@section('title','Warranty Registr')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Warranty Registration') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">
				@can('create warranty registration')
					<a class="btn btn-primary customModal" id="create_vehicle" href="#" data-size="lg"
						data-url="{{ route('warrentyRegistration.create') }}"
						data-title="{{ __('Create Warranty Registration') }}"> <i class="ti-plus"></i>
						{{ __('Create Warranty Registration') }}
					</a>
				@endcan
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
                        <table id="warrentyRegistrationList" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Warranty No</th>
                                    <th>Work Order</th>
                                    <th>Vehicle REG</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Invoice ID</th>
                                    <th>Warranty Period</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Status</th>
                                    <th>Claim Count</th>
                                    <th>Jump Start</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->
    @endsection
    @section('js')
        <script>
            $(function() {
                $('#warrentyRegistrationList').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 50,
                    ajax: "{{ route('warrentyRegistrationList') }}",
                    order: [
                        [0, 'desc']
                    ],
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                    columns: [{
                            data: 'id',
                            name: 'id',
                            render: data => `#REG-${data}`
                        },
                        {
                            data: 'warranty_no',
                            name: 'AddWarrantyItems.warrantynumber'
                        },
                        {
                            data: 'work_order',
                            name: 'work_order_id'
                        },
                        {
                            data: 'rego',
                            name: 'vehicleInfo.rego'
                        },
                        {
                            data: 'customer_name',
                            name: 'customer.first_name'
                        },
                        {
                            data: 'product_name',
                            name: 'product.product_name'
                        },
                        {
                            data: 'invoice_id',
                            name: 'workorder.inv.id'
                        },
                        {
                            data: 'warranty_period',
                            name: 'warranty_period'
                        },
                        {
                            data: 'warranty_start_date',
                            name: 'warranty_start_date'
                        },
                        {
                            data: 'warranty_end_date',
                            name: 'warranty_end_date'
                        },
                        {
                            data: 'status_label',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'claim_count',
                            name: 'claim_count'
                        },
                        {
                            data: 'jump_start',
                            name: 'jump_start'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]

                });
            });
        </script>
    @endsection
