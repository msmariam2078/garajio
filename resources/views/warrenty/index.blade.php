@extends('layouts.master')
@section('title','Warranty Claim')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />

<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
 
    @endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Warranty Claim')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">
            @if(Gate::check('create warranty claim'))
				<a class="btn btn-primary customModal" id="create_vehicle" href="#" data-size="lg"
					data-url="{{ route('warrentyitems.create') }}"
					data-title="{{ __('Create Warranty Claim') }}"> <i class="ti-plus"></i>
					{{ __('Create Warranty Claim') }}
				</a>
            @endif
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
                                <th>{{__('ID')}}</th>
                                <th>{{__('Customer name')}}</th>
                                <th>{{__('Product name')}}</th>
                                <th>{{__('Claim Date')}}</th>

                                <th>{{__('Claim status')}}</th>
                                <th>{{__('Service center')}}</th>




                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warrentyItems as $key=>$warrentyItem)
                            <tr>
                                <td data-order="{{ $key+1}}">#CLA000{{$key+1}}</td>
                                <td>{{ $warrentyItem->customer?->first_name }} </td>
                                <td>{{ $warrentyItem->servicePart?->product_name }} </td>
                                <td>{{ $warrentyItem->claim_date }} </td>
                                <td> @if($warrentyItem->status == 1)
                                    <span class="badge badge-success text-bold h5">Active</span>
                                    @elseif($warrentyItem->status == 2)
                                    <span class="badge badge-warning text-bold h5">Already Claimed</span>
                                  
                                    @endif</td>
                                <td>{{ App\Models\WarHouse::find($warrentyItem->service_center)->name ?? '' }} </td>

                                <td class="d-flex">
                                    @can('edit warranty claim')
										<a class="text-success customModal" data-bs-toggle="tooltip" data-size="lg"
											data-bs-original-title="{{ __('Edit') }}" href="#"
											data-url="{{ route('warrentyitems.edit', $warrentyItem->id) }}"
											data-title="{{ __('Edit Warranty Claim') }}">
											<img src="{{URL::asset('assets/img/icons/edit.svg')}}"
												style='width:25px;height:25px;'>

										</a>
                                    @endcan
                                    @can('delete warranty claim')
										{!! Form::open(['method' => 'DELETE', 'route' => ['warrentyitems.destroy',  $warrentyItem->id]])
										!!}
										<a class=" text-danger confirm_dialog" data-bs-toggle="tooltip"
											data-bs-original-title="{{ __('Delete') }}" href="#"> <img
												src="{{ URL::asset('assets/img/icons/trash.svg') }}"
												style='width:25px;height:25px;'></a>



										{!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                            @include('warrenty.delete')
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

<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
@endsection