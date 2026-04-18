@extends('layouts.master')
@section('title','Warehouse')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />



@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> Warehouse</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">
            <div class="btn-group dropdown">
                  @if (Gate::check('create warehouse'))
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#warhouseModal">Create
                    Ware House</button>
                @endif
            </div>
        </div>
    </div>
    @include('war_house.create')
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
                    <table class="table text-md-nowrap datatbl-advance" >
                        <thead>
                            <tr>
								<th class="d-none"></th>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Address') }}</th>
                                <th>{{ __('City') }}</th>
                                <th>{{ __('State') }}</th>
                                <th>{{ __('Country') }}</th>
                                <th>{{ __('Post Code') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Technician') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>

                        </thead>
                        <tbody>
							@foreach ($warHouses->sortByDesc('id')->values() as $key => $warHouse)
                            <tr>
								<td class="d-none"></td>
                                <td>WH-{{ $warHouse->id }}</td>
                                <td>{{ $warHouse->name }}</td>
                                <td>{{ $warHouse->address }}</td>
                                <td>{{ $warHouse->city }}</td>
                                <td>{{ $warHouse->state }}</td>
                                <td>{{ $warHouse->country }}</td>
                                <td>{{ $warHouse->zip_code }}</td>
                                <td>{{ $warHouse->type }}</td>
                                <td>{{ @$warHouse->user->first_name }}{{ @$warHouse->user->last_name }}</td>
                                <td class="d-flex">
                                    @can('edit warehouse')
                                    <a class="text-success customModal" data-bs-toggle="tooltip" data-size="lg"
                                        data-bs-original-title="{{ __('Edit') }}" href="#"
                                        data-url="{{ route('warehouse.edit', $warHouse->id) }}"
                                        data-title="{{ __('Edit Ware house') }}">
                                        <img src="{{URL::asset('assets/img/icons/edit.svg')}}"
                                            style='width:25px;height:25px;'></a>

                                    </a>
                                    @endcan
                                    

                                    {!! Form::open(['method' => 'DELETE', 'route' => ['warehouse.destroy', $warHouse->id]])
                                    !!}
                                    @can('delete warehouse')
                                    <a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
                                        data-bs-original-title="{{ __('Delete') }}" href="#"> <img
                                            src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                            style='width:25px;height:25px;'></a>
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