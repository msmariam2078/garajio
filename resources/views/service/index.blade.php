@extends('layouts.master')
@section('title', 'Services')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Services')}}</h4><span
                class="text-muted mt-1 tx-13 ml-2 mb-0">/ Table</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">

        <div class="mb-3 mb-xl-0">
            @if(Gate::check('create service'))
          <a class="btn btn-primary  customModal" href="#" data-size="lg"
                    data-url="{{ route('service.create') }}" data-title="{{__('Create Service')}}">
                    {{__('Create Service')}}
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
								<th class="d-none"></th>
                                <th>{{__('Sl')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Skill Group')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
							@foreach ($services->sortByDesc('id')->values() as $key => $service)
                            <tr>
								<td class="d-none"></td>
								<td>#S-{{ $service->id }}</td>
                                <td>{{ $service->title }}</td>
                                <td>{{ @$service->description }}</td>
                                <td>
                                    @php
                                    $skillIds = explode(',', $service->skillId);
                                    $skillNames = \App\Models\SkillGroup::whereIn('id',
                                    $skillIds)->pluck('group_name')->toArray();
                                    @endphp
                                    {{ implode(', ', $skillNames) }}
                                </td>
                                <td class="d-flex">
                                    @can('edit service')
                                    <a class="text-success customModal" data-bs-toggle="tooltip" data-size="lg"
                                            data-bs-original-title="{{__('Edit')}}" href="#"
                                            data-url="{{ route('service.edit',$service->id) }}"
                                            data-title="{{__('Edit Service')}}"> <img
                                                src="{{URL::asset('assets/img/icons/edit.svg')}}"
                                                style='width:25px;height:25px;'></a>
                                    @endcan

                                    @can('delete service')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['service.destroy', $service->id],
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
                            
                            @include('service.delete')
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