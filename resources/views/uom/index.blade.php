@extends('layouts.master')
@section('title','UOM')
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('UOM') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    @if (Gate::check('create uom'))
                        <a class="btn btn-primary customModal" href="#" data-size="lg"
                            data-url="{{ route('uom.create') }}" data-title="{{ __('Add UOM') }}"> <i class="ti-plus"></i>
                            {{ __('Create UOM') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @include('skill.create')
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
									<th class="d-none"></th>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($uom->sortByDesc('id')->values() as $key => $item)
                                    <tr>
										<td class="d-none"></td>
                                        <td>#U-{{ $item->id }} </td>
                                        <td>{{ $item->title }} </td>
                                        <td>
                                            <div class="cart-action">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['uom.destroy', $item->id]]) !!}
                                                @can('edit uom')
                                                    <a class="text-success customModal" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Edit') }}" href="#"
                                                        data-url="{{ route('uom.edit', $item->id) }}"
                                                        data-title="{{ __('Edit UOM') }}">
                                                        <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                            style='width:25px;height:25px;'>
                                                    </a>
                                                @endcan
                                                @can('delete uom')
                                                    <a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Delete') }}" href="#">
                                                        <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                            style='width:25px;height:25px;'>
                                                    </a>
                                                @endcan
                                                {!! Form::close() !!}
                                            </div>
                                    </tr>
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
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>

    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endsection
