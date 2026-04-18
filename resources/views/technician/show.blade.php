@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('Technician') }}</h4><span
                    class="text-muted mt-1 tx-13 ml-2 mb-0">/ Details</span>
            </div>
        </div> 
		<div class="d-flex my-xl-auto right-content">
			<a href="{{ route('technician.index') }}" class="btn btn-primary ml-2">
				<i class="fas fa-arrow-left"></i>
				Back
			</a>
		</div>
    </div>
@endsection
@section('content')
    <div class="tab-content p-4 br-dark">
        <div class="tab-pane active show" id="customer" role="tabpanel">
            <div class="card">
                <div class="card-body shadow">
                    <div class="card-header">
                        <h5>Personal Details</h5>
                    </div>
                    <div class="row ml-2">
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group mb-3">
								<label class="form-label">Name</label>
                				<p class="form-control">{{$technician->first_name }} {{$technician->last_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group mb-3">
								<label class="form-label">Email</label>
                                <p class="form-control">{{ $technician->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="detail-group mb-3">
								<label class="form-label">Phone Number</label>
                                <p class="form-control">
                                    +{{ preg_replace('/[^0-9]/', '', $technician->ccm) }}{{ $technician->phone_number }}
                                </p>
                            </div>
                        </div>
                    </div>

					<div class="card-header">
						<h5>{{ __('Service Address') }}</h5>
					</div>
					<div class="row ml-2">
						<div class="col-md-4 col-lg-4">
							<div class="detail-group mb-3">
								<label class="form-label">Country</label>
								<p class="form-control">
									{{ !empty($technician->clients) ? $technician->clients->service_country : '-' }}
								</p>
							</div>
						</div>
						<div class="col-md-4 col-lg-4">
							<div class="detail-group mb-3">
								<label class="form-label">State</label>
								<p class="form-control">
									{{ !empty($technician->clients) ? $technician->clients->service_state : '-' }}
								</p>
							</div>
						</div>
						<div class="col-md-4 col-lg-4">
							<div class="detail-group mb-3">
								<label class="form-label">City</label>
								<p class="form-control">
									{{ !empty($technician->clients) ? $technician->clients->service_city : '-' }}
								</p>
							</div>
						</div>
						<div class="col-md-4 col-lg-4">
							<div class="detail-group mb-3">
								<label class="form-label">Zip code</label>
								<p class="form-control">
									{{ !empty($technician->clients) ? $technician->clients->service_zip_code : '-' }}
								</p>
							</div>
						</div>
						<div class="col-md-4 col-lg-4">
							<div class="detail-group mb-3">
								<label class="form-label">Address</label>
								<p class="form-control">
									{{ !empty($technician->clients) ? $technician->clients->address : '-' }}
								</p>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>

    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
