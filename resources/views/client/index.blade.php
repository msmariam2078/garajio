@extends('layouts.master')
@section('title', 'Client List')
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Clients</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                @if (Gate::check('create customer'))
                    <a class="btn btn-primary  ml-20 customModal" href="#" data-size="lg"
                        data-url="{{ route('client.create') }}" data-title="{{ __('Create Client') }}"> <i
                            class="ti-plus mr-1"></i>
                        {{ __('Create Client') }}
                    </a>
                @endif
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
            <div class="card p-0 m-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="clients-list" class="table">
                            <thead>
                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th class="auto border-bottom-0">ID</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Name') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Email') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Phone Number') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Type') }}</th>
                                    <th class="wd-15p border-bottom-0">{{ __('Invoice') }}</th>
                                    <th class="">{{ __('Status') }}</th>
                                    <th class="pl-5 border-bottom-0">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#clients-list')) {
                $('#clients-list').DataTable().clear().destroy();
            }

            $('#clients-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('clients-list') }}',
                columns: [{
                        data: 'sl',
                        name: 'sl',
                        className: 'd-none'
                    },
                    {
                        data: 'client_id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'first_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone_number'
                    },
                    {
                        data: 'type',
                        name: 'client_type'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'status',
                        name: 'is_active',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 50,
                dom: 'Bfrtip',
                buttons: ['print', 'excel', 'pdf', 'csv', 'copy']
            });
        });
    </script>
@endsection
