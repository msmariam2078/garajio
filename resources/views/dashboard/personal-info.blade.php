@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Profile') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Page</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <section style="background-color: #ffffff;">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="ps-0">
                            <div class="main-profile-overview">
                                <span class="avatar avatar-xxl avatar-rounded main-img-user profile-user user-profile">
                                    <img src="{{ URL::asset($user->profile) }}" alt="" class="profile-img">
                                </span>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="main-profile-name">{{ $user->fullname }}</h6>
                                        <p class="main-profile-name-text text-muted">{{ $user->type }}</p>
                                    </div>
                                </div>
                                <hr class="border-0">
                                <div class="main-content-label tx-13 mg-b-25">
                                    contact
                                </div>
                                <div class="main-profile-contact-list">
                                    <div class="media">
                                        <div class="media-icon bg-primary-transparent text-primary">
                                            <i class="icon ion-md-phone-portrait"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>Phone</span>
                                            <div>
                                                +{{preg_replace('/[^0-9]/', '',  $user->ccm )}} {{ $user->phone_number }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-icon bg-success-transparent text-success">
                                            <i class="icon ion-logo-slack"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>Email</span>
                                            <div>
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-icon bg-info-transparent text-info">
                                            <i class="icon ion-md-locate"></i>
                                        </div>
                                        <div class="media-body">
                                            <span>Current Address</span>
                                            <div>
                                                {{ $user->clients->service_address ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-0">
                                <h6 class="fs-14 {{auth::user()->type != 'technician' ? 'd-none' : ''}}">Skills</h6>
								
                                @foreach ($skills as $item)
                                    <div class="skill-bar mb-4 clearfix">
                                        <span>{{ $item->group_name }}</span>
                                        <div class="progress progress-sm mt-2">
                                            <div class="progress-bar bg-success-gradient" role="progressbar"
                                                aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="mb-4 main-content-label">Full Information</div>						
						<form method="POST" action="{{ url('edit.user') }}" accept-charset="UTF-8" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="id" value="{{$user->id}}">
							<div class="form-group mb-3">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">First Name</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" name="first_name" placeholder="First Name"
											value="{{ $user->first_name }}">
									</div>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">last Name</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" name="last_name" placeholder="Last Name"
											value="{{ $user->last_name }}">
									</div>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">Designation</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="Designation"
											value="{{ $user->type }}" readonly>
									</div>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">Email</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="Email"
											value="{{ $user->email }}" readonly>
									</div>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">Phone</label>
									</div>
									<div class="col-md-9">										
										<div class="input-group mb-3">
											<select class="form-control col-md-4" name="ccm">
												@foreach ($country_code as $item)
													<option value="{{$item}}" {{ $item == $user->ccm ? 'selected' : '' }}>{{$item}}</option>
												@endforeach
											</select>
											<input type="text" class="form-control" name="phone_number" placeholder="phone number" value="{{ $user->phone_number }}">
										</div>											  
									</div>
								</div>
							</div>
							<div class="form-group mb-3 {{auth::user()->type != 'technician' ? 'd-none' : ''}}">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">Shift</label>
									</div>
									<div class="col-md-9">
										<div class="input-group mb-3">
											@foreach ($shifts as $item)
												<i class="fas fa-hourglass-start p-1"></i> <span class="input-group-text">{{ $item->title }}</span>
											@endforeach
										</div>                                        
									</div>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">Change password</label>
									</div>
									<div class="col-md-9">
										<input type="text" name="password" class="form-control" placeholder="Enter new password">
									</div>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">Change profile picture</label>
									</div>
									<div class="col-md-9">
										<input type="file" name="profile" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group mb-3">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label">Address</label>
									</div>
									<div class="col-md-9">
										<input type="text" name="service_address" class="form-control" placeholder="Address"
											value="{{ $user->clients->service_address ?? '' }}">
									</div>
								</div>
							</div>
							<div class="card-footer text-center p-2">
								<button type="submit" class="btn btn-primary waves-effect waves-light">Update Profile</button>
							</div>
						</form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
@endsection
