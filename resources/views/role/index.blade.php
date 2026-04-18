@extends('layouts.master')
@section('title','Roles')

@section('css')
<!-- Notify Plugin -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between align-items-center">
    <div>
        <h4 class="content-title mb-2">Roles</h4>
        <span class="text-muted">Role Management / Table View</span>
    </div>
    @can('create role')
    <div>
        <a href="{{ route('role.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Create Role
        </a>
    </div>
    @endcan
</div>
@endsection

@section('content')
@include('messages_alert')

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">All Roles</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-nowrap text-center" id="tableID">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-left">Role</th>
                                <th>Assigned Users</th>
                                <th>Assigned Permissions</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roleData as $role)
                            <tr>
                                <td class="text-left">{{ ucfirst($role->name) }}</td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ \Auth::user()->roleWiseUserCount($role->name) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $role->permissions()->count() }}
                                    </span>
                                </td>
                                <td class="text-center">

                                    @if (auth()->user()->type === 'super admin' || $role->name !== 'super admin')
										@can('edit role')
											<a href="{{ route('role.edit', $role->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
												<i class="fas fa-edit"></i>
											</a>
										@endcan
										
										@can('delete role')
											<button class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#delete_role{{$role->id}}" title="Delete">
												<i class="fas fa-trash-alt"></i>
											</button>
										@endcan
									@endif
                                </td>
                            </tr>
                            @include('role.delete')
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- table-responsive -->
            </div> <!-- card-body -->
        </div> <!-- card -->
    </div> <!-- col -->
</div> <!-- row -->
@endsection

@section('js')
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>
@endsection
