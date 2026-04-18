@extends('layouts.master')
@section('title','Transfer')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Transfer Orders</h4>
                <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">
				@if (Gate::check('create transfer order'))
					<a class="btn btn-primary " href="{{ route('transfer.create') }}">
						{{ __('Create Transfer Order') }}
					</a>
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
                        <table class="table text-md-nowrap datatbl-advance">
                            <thead>
                                <tr>
									<div class="d-none"></div>
                                    <th>Id</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('From Warehouse') }}</th>
                                    <th>{{ __('To Warehouse') }}</th>
                                    <th>{{ __('Item ID') }}</th>
                                    <th>{{ __('Item Name') }}</th>
                                    <th>{{ __('Qty Of Transfer') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach ($transfers->sortByDesc('id')->values() as $key => $transfer)
                                    <tr>
										<td class="d-none"></td>
                                        <td>#TR-{{ $transfer->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transfer->created_at)->format(' y-d-m') }}</td>
                                        <td>{{ $transfer->fromwarehouse?->name }}</td>
                                        <td>{{ $transfer->towarehouse?->name }}</td>
                                        <td>{{ $transfer->servicePart?->item_no }}</td>
                                        <td>{{ $transfer->servicePart?->product_name }}</td>
                                        <td>{{ $transfer->qty_transfer ?? 0 }}</td>
                                        <td>
                                            @can('delete transfer order')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['transfer.destroy', $transfer->id]]) !!}
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
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
@endsection
