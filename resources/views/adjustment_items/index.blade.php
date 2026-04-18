@extends('layouts.master')
@section('title','Adjustments')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Adjustments</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">
            @if (Gate::check('create adjustment'))
            <!-- <button class="btn btn-danger btn-sm ml-20 " id='post_all' style='padding:11px;'>post</button> -->
            <button type="btn btn-primary btn-sm ml-20 " class="btn btn-primary" data-toggle="modal"
                data-target="#adjustmentModal">Create Adjustment Item</button>
           @endif

        </div>
    </div>
    @include('adjustment_items.create')
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
                    <table  class="table text-md-nowrap datatbl-advance">
                        <thead>
                            <tr>
								<th class="d-none"></th>
                                <th>Id</th>
                               	<th>{{__('Item No.')}}</th>
                                <th>{{__('Item Name')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Location')}}</th>
                                <th>{{__('Make')}}</th>
                                {{-- <th>{{__('Reference')}}</th> --}}
                                <th>{{__('Unit')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Unit Price')}}</th>
                                <th>{{__('Total Price')}}</th>
                                <th>{{__('Warranty')}}</th>
                                {{-- <th>{{__('Expire Date')}}</th> --}}
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
							@foreach ($adjustments->sortByDesc('id')->values() as $key => $adjustment)
                            <tr>
								<td class="d-none"></td>
                                <td>#A-{{ $adjustment->id }}</td>
                                <td>{{ $adjustment->servicePart?->item_no }}</td>
                                <td>{{ $adjustment->servicePart?->product_name }}</td>
                                <td>{{ $adjustment->servicePart->item_type }} </td>
                                <td>{{ $adjustment->warehouse->name }} </td>                             

                                <td>adjustment </td>
                                <td>unit </td>
                                <td>{{$adjustment->quantity}} </td>
                                <td>{{$adjustment->servicePart->price}} </td>
                                <td>{{(int)$adjustment->servicePart->price*(int)$adjustment->servicePart->price}}</td>
                                <td>{{$adjustment->servicePart->warranty}} </td>
                                <td>
                                    @can('edit adjustment')
                                    <a class="" href="#" data-toggle="modal"
                                        data-target="#update_adjustment{{$adjustment->id}}">
                                        <img src="{{URL::asset('assets/img/icons/edit.svg')}}"
                                            style='width:25px;height:25px;'>
                                    </a>
                                    @endcan
                                    @can('delete adjustment')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['adjustment_item.destroy', $adjustment->id],
                                    'style' => 'display:inline-block;']) !!}
                                    <a class="border-0 bg-transparent confirm_dialog"
                                        data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}">
                                        <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                            style="width:25px;height:25px;">
                                    </a>
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                            @include('adjustment_items.edit')
                       
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






<script type="text/javascript">
$(function() {
    $("#post_all").click(function() {
        var selected = [];
        $("#main_table .post_select:checked").each(function() {
            selected.push(this.value);

        });
        console.log(selected);
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({

            url: '{{ route('adjustment_item.post_all') }}',
            type: "get",
            dataType: 'json',

            data: {
                ids: selected
            },

            success: function(response) {
                window.onload = function() {
                    notif({
                        msg: "{{ 'updated'}}",
                        type: "success"
                    });
                }



            },
        });


    });

});
</script>
@endsection