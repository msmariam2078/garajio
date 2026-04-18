@extends('layouts.master')

@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Create Role And Permissions </h4>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')
    @php
        $systemModules = \App\Models\User::$systemModules;
    @endphp
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['url' => 'role']) }}
                        <div class="form-group px-5">
                            {{ Form::label('title', __('Role Title'), ['class' => 'form-label']) }}
                            {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter role title')]) }}
                        </div>

                        <div class="row px-5">
                            <div class="col-xl-12 col-md-12">
                                <table class="table table-bordered">
                                    <thead class="d-none">
                                        <tr>
                                            <th scope="col">Module name</th>
                                            <th scope="col">View</th>
                                            <th scope="col">Create</th>
                                            <th scope="col">Edit</th>
                                            <th scope="col">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($systemModules as $module)
                                            <tr>
                                                <td>
                                                    <div class="badge badge-primary head">{{ $module }}</div>
                                                </td>     
                                                @foreach ($permissionList as $permission)
                                                    @if ($permission->module == $module)
                                                        <td>
                                                            <div class="form-check">
                                                                {{ Form::checkbox('user_permission[]', $permission->id, null, ['class' => 'form-check-input', 'id' => $module . '_permission' . $permission->id]) }}
                                                                {{ Form::label($module . '_permission' . $permission->id, ucfirst($permission->name), ['class' => 'form-check-label']) }}
                                                            </div>
                                                        </td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach                                    
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                {{ Form::submit(__('Create now'), ['class' => 'btn btn-primary col-md-6']) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".head").on("click", function(e) {
            e.preventDefault();
            jQuery("input[name='user_permission[]']").each(function(index) {

                if ($(this).next('label').text().indexOf(e.target.innerText) > -1)
                    $(this).prop('checked', true);
            });


        });
    </script>
@endsection


@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
