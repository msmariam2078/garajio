@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Customer Group</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">
                @if (Gate::check('create customer group'))
                    <div class="btn-group dropdown">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cgroupModal">
                            <i class="ti-plus"></i>
                            Create customer group
                        </button>
                    </div>
                @endif
            </div>
        </div>
        @include('customergroup.create')
    </div>
@endsection
@section('content')
    @include('messages_alert')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-brdered datatbl-advance">
                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Group Name') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupname as $key => $item)
                                    <tr>
                                        <td width="20" class="text-center">{{ $item->id }} </td>
                                        <td>{{ $item->group_name }} </td>
                                        <td>
                                            @can('edit customer group')
                                                <a class="" href="#" data-toggle="modal"
                                                    data-target="#update_cgroup{{ $item->id }}">
                                                    <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                            @endcan
                                            @can('delete customer group')
                                                <a class="" href="#" data-toggle="modal"
                                                    data-target="#delete_cgroup{{ $item->id }}">
                                                    <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                            @endcan




                                        </td>



                                    </tr>
                                    @include('customergroup.edit')
                                    @include('customergroup.delete')
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- bd -->
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
