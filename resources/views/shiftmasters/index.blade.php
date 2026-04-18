@extends('layouts.master')
@section('title','Shift Master')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Shift Masters')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">

            <div class="btn-group dropdown">
                   @if(Gate::check('create shift'))
                <a class="btn btn-primary  customModal" href="#" data-size="lg"
                    data-url="{{ route('shiftmasters.create') }}" data-title="{{__('Create Shift Masters')}}">
                    {{__('Create Shift Masters')}}
                </a>
@endif
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
								<th>Id</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Days</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
							@foreach ($shifts->sortByDesc('id')->values() as $key => $shift)
                            <tr>
								<td class="d-none"></td>
                                <td>SM-{{ $shift->id }}</td>
                                <td>{{ $shift->title }}</td>
                                <td>{{ $shift->description }}</td>
                                <td>{{ implode(',',json_decode($shift->days,true)) }}</td>
                                <td>{{ $shift->start_time }}</td>
                                <td>{{ $shift->end_time }}</td>
                                <td>
                                    @can('edit shift')
                                    <a class="text-success customModal" data-bs-toggle="tooltip"
                                        data-bs-original-title="{{__('Edit')}}" href="#"
                                        data-url="{{ route('shiftmasters.edit', $shift->id) }}"
                                        data-title="{{__('Edit Shift Masters')}}">
                                        <img src="{{URL::asset('assets/img/icons/edit.svg')}}"
                                            style='width:25px;height:25px;'>
                                    </a>
                                @endcan
                                    <form action="{{ route('shiftmasters.destroy', $shift->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        @can('delete shift')
                                        <a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{__('Delete')}}" href="#">
                                            <img src="{{URL::asset('assets/img/icons/trash.svg')}}"
                                                style='width:25px;height:25px;'>
                                        </a>
                                        @endcan
                                    </form>
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


<script>
$(function() {
    $("select").selectize();
});
</script>
<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>

@endsection