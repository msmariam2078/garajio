@extends('layouts.master')
@section('title', 'Items')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Service & Part</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="mb-3 mb-xl-0">
                <div class="btn-group dropdown">
                    @if (Gate::check('create service & part'))
                        <a class="btn btn-primary  customModal" href="#" data-size="lg"
                            data-url="{{ route('services-parts.create') }}" data-title="{{ __('Create Service & Part') }}">
                            {{ __('Create Service & Part') }}
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
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Item Number') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Item Type') }}</th>
                                    <th>{{ __('Quantity on hand') }}</th>
                                    <th>{{ __('Quantity ') }}</th>
                                    <th>{{ __('Purchase Price') }}</th>
                                    <th>{{ __('Sales Price') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Warranty') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach ($serviceParts->sortByDesc('id')->values() as $key => $servicePart)
                                    <tr>
										<td class="d-none"></td>
                                        <td>#SP-{{ $servicePart->id }}</td>
                                        <td>{{ $servicePart->item_no }}</td>
                                        <td>{{ $servicePart->product_name }} </td>
                                        <td>
                                            @if (strtoupper($servicePart->item_type) == 'INVENTORY')
                                                <span
                                                    class="badge badge-info">{{ strtoupper($servicePart->item_type) }}</span>
                                            @elseif(strtoupper($servicePart->item_type) == 'BUNDLE')
                                                <span
                                                    class="badge badge-primary">{{ strtoupper($servicePart->item_type) }}</span>
                                            @elseif(strtoupper($servicePart->item_type) == 'SERVICE')
                                                <span
                                                    class="badge badge-success">{{ strtoupper($servicePart->item_type) }}</span>
                                            @elseif(strtoupper($servicePart->item_type) == 'SCRAP')
                                                <span
                                                    class="badge badge-warning">{{ strtoupper($servicePart->item_type) }}</span>
                                            @else
                                                <span
                                                    class="badge badge-light">{{ strtoupper($servicePart->item_type) }}</span>
                                            @endif
                                        </td>

                                        <td>{{ $servicePart->qty_on_hand }}</td>
                                        <td>{{ $servicePart->quantity }}</td>
                                        <td>{{ $servicePart->price }}</td>
                                        <td>{{ $servicePart->sales_price }}</td>
                                        <td>{{ $servicePart->category_description ?? 'N/A' }}</td>
                                        <td>{{ $servicePart->warranty }}</td>
                                        <td>{{ $servicePart->tax }}</td>
                                        <td>
                                            <div class="cart-action">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['services-parts.destroy', $servicePart->id]]) !!}

                                                @can('edit service & part')
                                                    <a class="text-success customModal" data-bs-toggle="tooltip" data-size="lg"
                                                        data-bs-original-title="{{ __('Edit') }}" href="#"
                                                        data-url="{{ route('services-parts.edit', $servicePart->id) }}"
                                                        data-title="{{ __('Edit Service & Part') }}"> <img
                                                            src="{{ URL::asset('assets/img/icons/edit.svg') }}"
                                                            style='width:25px;height:25px;'></a>
                                                @endcan
                                                @can('delete service & part')
                                                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Detete') }}" href="#"> <img
                                                            src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                                            style='width:25px;height:25px;'></a>
                                                @endcan
                                                {!! Form::close() !!}
                                            </div>

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
