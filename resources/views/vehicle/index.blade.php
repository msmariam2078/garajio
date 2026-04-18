@extends('layouts.master')
@section('title', 'Equipment')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Equipment') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a class="btn btn-primary customModal" id="create_vehicle" href="#" data-size="lg"
                data-url="{{ route('vehicle.create') }}" data-title="{{ __('Create Equipment') }}"> <i class="ti-plus"></i>
                {{ __('Create Equipment') }}
            </a>
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
                        <table id="vehicles-table" class="table">
                            <thead>
                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th class="text-center">ID</th>
                                    <th>Reg No</th>
                                    <th>Model</th>
                                    <th>Make</th>
                                    <th>Customer Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#vehicles-table')) {
                $('#vehicles-table').DataTable().clear().destroy();
            }

            $('#vehicles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('vehicles-list') }}',
                columns: [{
                        data: 'sl',
                        name: 'sl',
                        className: 'd-none'
                    },

                    {
                        data: 'id',
                        name: 'id',
                        className: 'text-center'
                    },

                    {
                        data: 'rego',
                        name: 'rego'
                    },
                    {
                        data: 'model',
                        name: 'vehicle_models.model_name'
                    },
                    {
                        data: 'make',
                        name: 'vehicle_makes.make_name'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'desc']
                ], // order by ID descending
                pageLength: 50,
                dom: 'Bfrtip',
                buttons: ['print', 'excel', 'pdf', 'csv', 'copy']
            });
        });
    </script>
@endsection
