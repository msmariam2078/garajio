@extends('layouts.master')
@section('title', 'User')

@php
    $profile = asset(Storage::url('upload/profile/'));
@endphp
@section('css')
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between container-fluid">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">User </h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                @if (Gate::check('create user'))
                    <div class="btn-group dropdown">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userModal">Create
                            user
                        </button>
                @endif
            </div>
        </div>
    </div>
    @include('user.create')
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @include('messages_alert')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mg-b-0 text-md-nowrap datatbl-advance text-center" id='tableID'>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Email</th>
                                    <th class="text-left">Phone Number</th>
                                    <th class="text-left">Assign Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
									@php
										$profilePath = public_path($user->profile ?? '');
										$image = \Illuminate\Support\Facades\File::exists($profilePath) && $user->profile
											? asset($user->profile)
											: asset('assets/img/media/avatar2.webp');
									@endphp
                                    <tr>
										<td>{{$user->id}}</td>
                                        <td class="table-user">
                                            <img src="{{ $image }}" alt="" class="rounded-circle user-avatar">
                                        </td>
										<td class="text-left">{{ $user->full_name }}</td>
                                        <td class="email-column text-left">{{ $user->email }}</td>
                                        <td class="text-left">{{ $user->full_phone }}</td>
                                        <td class="text-left">{{ ucfirst($user->type) }} </td>
                                        <td>
											{{-- @if ($user->type !== 'super admin' && $user->type !== auth()->user()->type)							 --}}
												@can('edit user')
													<a href="#" data-toggle="modal"
														data-target="#update_user{{ $user->id }}">
														<img src="{{ asset('assets/img/icons/edit.svg') }}"
															style="width:25px;height:25px;">
													</a>
												@endcan
                                            	@can('delete user')                                                
                                                    <a href="#" data-toggle="modal"
                                                        data-target="#delete_user{{ $user->id }}">
                                                        <img src="{{ asset('assets/img/icons/trash.svg') }}"
                                                            style="width:25px;height:25px;">
                                                    </a>
												@endcan
											{{-- @endif --}}
                                        </td>
                                    </tr>
                                    @include('user.edit')
                                    @include('user.delete')
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
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
