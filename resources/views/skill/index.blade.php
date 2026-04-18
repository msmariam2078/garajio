@extends('layouts.master')
@section('title','Skills')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Skill')}}</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">
            @if(Gate::check('create skill'))
            <div class="btn-group dropdown">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#skillModal">
					<i class="ti-plus"></i>
					Create Skill
				</button>
            </div>
            @endif
        </div>
    </div>
    @include('skill.create')
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
                    <table class="table table-bordered datatbl-advance">
                        <thead>
                            <tr>
								<th class="d-none"></th>
                                <th>{{__('Id')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skills->sortByDesc('id')->values() as $key => $skill)
                            <tr>
                                <td class="d-none"></td>
                                <td>#S-{{ $skill->id }} </td>
                                <td>{{ $skill->skill_name }} </td>
                                <td class='d-flex'>
                                    @can('edit skill')
                                    <a class="" href="#" data-toggle="modal" data-target="#update_skill{{$skill->id}}">
                                        <img src="{{URL::asset('assets/img/icons/edit.svg')}}"
                                            style='width:25px;height:25px;'>
                                    </a>
                                    @endcan
                                    @can('delete skill')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['skill.destroy',  $skill->id]]) !!}
                                <a class="text-danger confirm_dialog" data-bs-toggle="tooltip"
                                    data-bs-original-title="{{ __('Delete') }}" href="#">
                                    <img src="{{ URL::asset('assets/img/icons/trash.svg') }}"
                                        style="width:25px;height:25px;">
                                </a>
                                {!! Form::close() !!}
                                    @endcan



                                </td>






                            </tr>
                            @include('skill.edit')
                     
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