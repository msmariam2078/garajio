@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Edit Role And Permissions</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @php
        $systemModules = \App\Models\User::$systemModules;
    @endphp
    @include('messages_alert')
    <div class="row">
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($role, ['route' => ['role.update', $role->id], 'method' => 'PUT']) }}
                    <div class="form-group">
                        {{ Form::label('title', __('Role Title'), ['class' => 'form-label']) }}
                        {{ Form::text('title', $role->name, [
                            'class' => 'form-control',
                            'placeholder' => __('Enter role title'),
                            in_array($role->name, ['tenant', 'maintainer']) ? 'readonly' : '',
                        ]) }}
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($systemModules as $module)
                                        <tr>
                                            <td>
												<h5>
													{{ ucfirst($module) }}
												</h5>
                                            </td>
											<td>
                                            	@foreach ($permissionList as $permission)
                                                	@if ($permission->module == $module)
                                                        <div class="form-check">
                                                            {{ Form::checkbox('user_permission[]', $permission->id, in_array($permission->id, $assignPermission), [
                                                                'class' => 'form-check-input',
                                                                'id' => $module . '_permission' . $permission->id,
                                                            ]) }}
                                                            {{ Form::label($module . '_permission' . $permission->id, ucfirst($permission->name), ['class' => 'form-check-label']) }}
                                                        </div>
													@endif
												@endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            {{ Form::submit(__('Update now'), ['class' => 'btn btn-primary col-md-6']) }}
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
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Select All Permissions
            $("#selectAll").click(function() {
                $("input[type=checkbox]").prop("checked", true);
            });

            // Unselect All Permissions
            $("#unselectAll").click(function() {
                $("input[type=checkbox]").prop("checked", false);
            });
        });
    </script>

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
