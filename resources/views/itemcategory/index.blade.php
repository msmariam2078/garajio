@extends('layouts.master')
@section('title','Item Category')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Item Category')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">
            <div class="btn-group dropdown">

            </div>
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
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap datatbl-advance">
                        <thead>
                            <tr>
								<th class="d-none"></th>
                                <th>{{ __('SN') }}</th>
                                <th>{{ __('Parent') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Has Children') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
							@foreach ($categories->sortByDesc('id')->values() as $key => $item)
                            <tr>
								<td class="d-none"></td>
                                <td>IC-{{ $item->id }}</td>
                                <td>{{ optional($item->parent)->description ?? '-' }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->children ? 'Yes' : 'No' }}</td>
                                <td>
                                    <div class="cart-action">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['categories.destroy',
                                        $item->id]]) !!}

                                        @can('edit item category')
											<a class="text-success customModal" data-bs-toggle="tooltip"
												data-bs-original-title="{{ __('Edit') }}" href="#"
												data-url="{{ route('brand.edit', $item->id) }}"
												data-title="{{ __('Edit Category') }}">
												<img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
													style="width:25px; height:25px;">
											</a>
                                        @endcan

                                        @can('delete item category')
											<a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
												data-bs-original-title="{{ __('Delete') }}" href="#">
												<img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
													style="width:25px; height:25px;">
											</a>
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
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>

<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>


@endsection