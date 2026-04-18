@extends('layouts.master')
@section('title','Warranty Extend')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Warranty Extend') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    @can('create warranty extend')
                        <a class="btn btn-primary customModal" href="#" data-size="lg"
                            data-url="{{ route('warrentyextend.create') }}" data-title="{{ __('Create Warranty Extend') }}"> <i
                                class="ti-plus"></i>
                            {{ __('Create Warranty Extend') }}
                        </a>
                    @endcan
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
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Customer name') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Purchase Date') }}</th>

                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Extend Start Date') }}</th>
                                    <th>{{ __('Extend End Date') }}</th>
                                    <th>{{ __('Coverage type') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warrentyExtends as $key => $warrentyExtend)
                                    <tr>
                                        <td data-order="{{ $key + 1 }}">#EXT000{{ $key + 1 }}</td>
                                        <td>{{ @$warrentyExtend->customer->first_name }} </td>
                                        <td>{{ @$warrentyExtend->servicePart->product_name }} </td>
                                        <td>{{ $warrentyExtend->purchase_date }} </td>
                                        <td>{{ $warrentyExtend->duration }} </td>
                                        <td>{{ $warrentyExtend->extend_start_date }} </td>
                                        <td>{{ $warrentyExtend->extend_end_date }} </td>
                                        <td>{{ $warrentyExtend->coverage_type }} </td>
                                        <td>{{ $warrentyExtend->price }} </td>
                                        <td>
                                            @if ($warrentyExtend->status == 0)
                                                <span class="badge badge-warning text-bold h5">Unpaid</span>
                                            @elseif($warrentyExtend->status == 1)
                                                <span class="badge badge-success text-bold h5">Paid</span>
                                            @endif
                                        <td class="d-flex">
                                           
                                                <a class="text-success customModal" data-bs-toggle="tooltip" data-size="lg"
                                                    data-bs-original-title="{{ __('Edit') }}" href="#"
                                                    data-url="{{ route('warrentyextend.edit', $warrentyExtend->id) }}"
                                                    data-title="{{ __('Edit Warranty Extend') }}">
                                                    <img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                        style='width:25px;height:25px;'></a>

                                                </a>
                                           
                                            @can('delete warranty extend')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['warrentyextend.destroy', $warrentyExtend->id]]) !!}
                                                <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Delete') }}" href="#"> <img
                                                        src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                        style='width:25px;height:25px;'></a>
                                                {!! Form::close() !!}
                                            @endcan
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
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
