@extends('layouts.master')
@section('title','Technician')
@section('css')
	<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{ __('Technician') }}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        @if (Gate::check('create technician'))
			<a class="btn btn-primary ml-20 customModal" href="#" data-size="lg" data-url="{{ route('technician.create') }}"
				data-title="{{ __('Create Technician') }}"> <i class="ti-plus mr-1"></i>
				{{ __('Create Technician') }}
			</a>
        @endif
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
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Profile Picture') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('type') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone Number') }}</th>
                                <th>{{ __('Warehouse Name') }}</th>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Address') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        </thead>
                        <tbody>
							@foreach ($technicians->sortByDesc('id')->values() as $key => $technician)
                            <tr>
								<td class="d-none"></td>
								<td>#Tec-{{$technician->id}}</td>
                                <td>
                                    <img src="{{ asset($technician->profile) }}" alt="" class="img-thumbnail"
                                        style="width: 100px; height: auto;">
                                </td>
                                <td>{{ $technician->full_name }} </td>
                                <td>{{ $technician->type }} </td>
                                <td class="email-column">{{ $technician->email }} </td>
                                <td>+{{preg_replace('/[^0-9]/', '',  $technician->ccm )}}{{ !empty($technician->phone_number) ? $technician->phone_number : '-' }} </td>
                                <td>
                                    @if ($technician->warehouses->count())
                                    @foreach ($technician->warehouses as $warehouse)
                                    {{ $warehouse->name }}@if (!$loop->last), @endif
                                    @endforeach
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ !empty($technician->tech_skillgroup) ? $technician->tech_skillgroup->group_name : '-' }}
                                </td>
                                <td>{{ !empty($technician->clients->service_address) ? \Illuminate\Support\Str::limit($technician->clients->service_address, 50) : null }}
                                </td>

                                </td>
                                <td class="text-center">
                                    <form action="{{ route('technician.updateStatus', $technician->id) }}" method="POST" class="status-form">
                                        @csrf
                                        <label class="custom-switch">
                                            <input type="checkbox" name="is_active" class="toggle-status"
												onchange="this.form.submit()" {{ $technician->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <div class="cart-action">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['technician.destroy',
                                        $technician->id]]) !!}
                                        @can('show technician')
											<a class="text-warning" data-bs-toggle="tooltip"
												data-bs-original-title="{{ __('Details') }}"
												href="{{ route('technician.show', \Illuminate\Support\Facades\Crypt::encrypt($technician->id)) }}">
												<img src="{{ URL::asset('assets/img/icons/eye.svg') }}"
													style='width:25px;height:25px;'>
											</a>
                                        @endcan
                                        @can('edit technician')
											<a class="text-success customModal pl-2" data-bs-toggle="tooltip" data-size="lg"
												data-bs-original-title="{{ __('Edit') }}" href="#"
												data-url="{{ route('technician.edit', $technician->id) }}"
												data-title="{{ __('Edit Technician') }}">
												<img src="{{ URL::asset('assets/img/icons/edit.svg') }}"
													style='width:25px;height:25px;'>
											</a>
                                        @endcan
                                        @can('delete technician')
											<a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
												data-bs-original-title="{{__('Detete')}}" href="#"> <img
													src="{{URL::asset('assets/img/icons/trash.svg')}}"
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
    <!--/div-->

</div>

@endsection
@section('js')
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>

<script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
@endsection