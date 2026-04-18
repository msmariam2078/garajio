@extends('layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Services & Parts with Vehicle') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
					@if(Gate::check('create item with vehicle'))
						<a class="btn btn-primary  customModal" href="#" data-size="lg"
							data-url="{{ route('servicepartwithvehicle.create') }}"
							data-title="{{ __('Create Services & Parts with Vehicle') }}">

							{{ __('Create Services & parts with Vehicle') }}
						</a>
					@endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <!-- row opened -->
    <div class="row row-sm">

        <div class="col-xl-12">
            <div class="card">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="servicePartsList" class="table">
                            <thead>
                                <tr>
                                    <th class="d-none">Sl</th>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Service Part Item') }}</th>
                                    <th>{{ __('Vehicle Make') }}</th>
                                    <th>{{ __('Vehicle Modal Type') }}</th>
                                    <th>{{ __('Year of Manufacture') }}</th>
                                    <th>{{ __('Engine Spec') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#servicePartsList')) {
                $('#servicePartsList').DataTable().clear().destroy();
            }

            $('#servicePartsList').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('servicePartsList') }}', // your data route
                columns: [{
                        data: 'sl',
                        name: 'sl',
                        className: 'd-none'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'service_part',
                        name: 'service_part'
                    },
                    {
                        data: 'make',
                        name: 'make'
                    },
                    {
                        data: 'model',
                        name: 'model'
                    },
                    {
                        data: 'yom',
                        name: 'yom'
                    },
                    {
                        data: 'engine_spec',
                        name: 'engine_spec'
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
                pageLength: 50,
                dom: 'Bfrtip',
                buttons: ['print', 'excel', 'pdf', 'csv', 'copy'],
                order: [
                    [1, 'desc']
                ]
            });
        });
    </script>
@endsection
