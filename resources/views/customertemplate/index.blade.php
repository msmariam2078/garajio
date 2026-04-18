@extends('layouts.master')
@section('title','Customer Template')
@section('css')
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Customer Template</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Table</span>
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
                                <th>{{__('SN')}}</th>
                                <th>{{__('Code')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Contact Type')}}</th>
                            </tr>
                        </thead>
                        <tbody>
							@foreach ($customertemplate->sortByDesc('id')->values() as $key => $item)
                            <tr>
								<td class="d-none"></td>
                                <td>CT-{{ $item->id }} </td>
                                <td>{{ $item->code }} </td>
                                <td>{{ $item->description }} </td>
                                <td>{{ $item->contact_type }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- bd -->
            </div><!-- bd -->
        </div><!-- bd -->
    </div>
</div>
<!-- main-content closed -->
@endsection
@section('js')
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
@endsection