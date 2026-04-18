@extends('layouts.master')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Client's vehicles</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <a href="{{ route('client.index) }}" class="btn btn-primary ml-2">
            <i class="fas fa-arrow-left"></i> {{__('Back')}}
        </a>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

@error('vehicle_id')
<span class="text-danger" style="font-size: 20px;">{{ $message }}</span>
@enderror
<div class="row row-sm">

    <div class="col-xl-12">
        <div class="card">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0 text-md-nowrap">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Registration Number') }}</th>
                                <th>{{ __('Make') }}</th>
                                <th>{{ __('Model') }}</th>

                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicles as $kay => $vehicle)
                            <tr>
                                <td>{{ $kay + 1 }}</td>
                                <td>{{ $vehicle->rego }} </td>
                                <td>{{ $vehicle->vehicle_makes->make_name }} </td>
                                <td>{{ $vehicle->vehicle_models->model_name }} </td>

                                <td>
                                    <form action="{{ route('vehicle.updateStatus', $vehicle->id) }}" method="POST"
                                        class="status-form">
                                        @csrf
                                        <label class="switch">
                                            <input type="hidden" name="status" value="0">
                                            <input type="checkbox" data-toggle="toggle" data-size="sm" name="status"
                                                value='1' onchange="this.form.submit()"
                                                {{ $vehicle->status == 1 ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </form>
                                </td>
                                <td> @can('view vehicle')
                                    <a class="text-warning customModal" data-bs-toggle="tooltip" data-size="lg"
                                        data-bs-original-title="{{ __('Details') }}" href="#"
                                        data-url="{{ route('vehicle.show', \Illuminate\Support\Facades\Crypt::encrypt($vehicle->id)) }}"
                                        data-title="{{ __('Vehicle') }}">
                                        <img src="{{URL::asset('assets/img/icons/eye.svg')}}"
                                            style='width:25px;height:25px;'>
                                    </a>
                                    @endcan
                                    @can('edit vehicle')
                                    <a class="text-success customModal" data-bs-toggle="tooltip" data-size="lg"
                                        data-bs-original-title="{{ __('Edit') }}" href="#"
                                        data-url="{{ route('vehicle.edit', $vehicle->id) }}">
                                        <img src="{{URL::asset('assets/img/icons/edit.svg')}}"
                                            style='width:25px;height:25px;'>
                                    </a>
                                    @endcan

                                    @endforeach
                                </td>
                        </tbody>
                    </table>
                </div><!-- bd -->
            </div><!-- bd -->
        </div><!-- bd -->
    </div>
    <!--/div-->




</div>
<!-- /row -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')



<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>

@endsection