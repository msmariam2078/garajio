@extends('layouts.master')
@section('title','Engine Specs')
@section('css')
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('EngineSpecs') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    @if (Gate::check('create enginespecs'))
                        <a class="btn btn-primary customModal" href="#" data-size="lg"
                            data-url="{{ route('enginespecification.create') }}" data-title="{{ __('Add Engine Specs') }}">
                            <i class="ti-plus"></i>
                            {{ __('Create Engine Specs') }}
                        </a>
                    @endif
                </div>
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
                        <table class="table text-md-nowrap datatbl-advance">
                            <thead>
                                <tr>
									<th class="d-none"></th>
                                    <th>Id</th>
                                    <th> Make</th>
                                    <th> Model</th>
                                    <th> Engine Specs</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach ($enginespecs->sortByDesc('id')->values() as $key => $item)
                                    <tr>
										<td class="d-none"></td>
                                        <td width="10" class="text-center">#ES-{{ $item->id }}</td>
                                        <td>{{ $item->make?->make_name }}</td>
                                        <td>{{ $item->model?->model_name }} </td>
                                        <td>{{ $item->enginespecs }} </td>
                                        <td>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['enginespecification.destroy', $item->id]]) !!}
                                            @can('edit enginespecs')
                                                <a class="text-success customModal" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Edit') }}" href="#"
                                                    data-url="{{ route('enginespecification.edit', $item->id) }}"
                                                    data-title="{{ __('Edit Skill') }}">
                                                    <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                            @endcan
                                            @can('delete enginespecs')
                                                <a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Delete') }}" href="#">
                                                    <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                        style='width:25px;height:25px;'>
                                                </a>
                                            @endcan
                                            {!! Form::close() !!}
                                        </td>
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
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
