@extends('layouts.master')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Permissions') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                @if (Gate::check('create permission'))
                    <a class="btn btn-primary customModal" href="#" data-size="md"
                        data-url="{{ route('permission.create') }}" data-title="{{ __('Create New Permission') }}"> <i
                            class="ti-plus"></i>{{ __('Create Permission') }}</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                    	<table class="table mg-b-0 text-md-nowrap">
                            <thead>
                                <tr>
                                    <th>{{ __('Module') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissionData as $module => $permissions)
                                    <tr>
                                        <td rowspan="{{ count($permissions) + 1 }}"><strong>{{ $module }}</strong>
                                        </td>
                                    </tr>
                                    @foreach ($permissions as $data)
                                        <tr>
                                            <td>{{ $data->name }}</td>
                                            <td>
                                                @can('delete permission')
                                                    <a href="#" data-toggle="modal"
                                                        data-target="#delete_permission{{ $data->id }}">
                                                        <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                            style="width:25px;height:25px;">
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @include('user_permission.delete')
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>

                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->




    </div>
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
