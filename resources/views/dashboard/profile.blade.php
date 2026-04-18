@extends('layouts.master')
@section('title', 'Dashboard')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<style>
	.slider-nav {
		position: absolute;
		top:38%;
		transform: translateY(-50%);
		background-color: rgba(0, 0, 0, 0.4);
		color: white;
		border: none;
		border-radius: 50%;
		width: 30px;
		height: 30px;
		font-size: 20px;
		cursor: pointer;
		z-index: 10;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: background 0.2s ease;
	}
	.slider-nav:hover {
		background-color: rgba(0, 0, 0, 0.6);
	}
	#prev-btn {
		left: 10px;
	}
	#next-btn {
		right: 10px;
	}
</style>
@endsection
@section('page-header')
	<div class="breadcrumb-header justify-content-between">
		<div class="my-auto">
			<div class="d-flex">
				<h4 class="content-title mb-0 my-auto">Technician</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
					Profile</span>
			</div>
		</div>
	</div>
	<div id="alert-container"></div>
@endsection
@section('content')
	@if(auth()->user()->type == 'technician')
		<div id="tasksListDashboard"></div>
		<div id="quotationList"></div>
	@endif


	<div class="row row-sm">
		<div class="col-lg-3">
			<div class="card mg-b-20">
				<div class="card-body p-2" style="background:lemonchiffon">
					<div class="main-profile-overview">
						<h4>Welcome Back, {{\Auth::user()->first_name}} {{\Auth::user()->last_name}}</h4>
						<h6 class="mt-3">Status</h6>
						@if(auth()->user()->type == 'technician')
							@if ($status?->lat == null)
								<button id="checkin-button" class="btn btn-success btn-block py-1">
									<strong>
										Check - IN
									</strong>
								</button>
							@else
								<button id="checkout-button" class="btn btn-danger btn-block py-1">
									<strong>
										Check - OUT
									</strong>
								</button>
								<button id="break-button" class="btn text-white  bg-warning border d-none">{{ __('BREAK') }}</button>
								<button id="unbreak-button" class="btn text-white  bg-secondary border d-none">{{ __('UNBREAK') }}</button>
							@endif
						@endif
					</div>
				</div>
			</div>

			@if(count($pendingWork)>0)
				<div class="card">
					<h5 class="text-primary text-center pt-1">Upcoming Job Cards</h5>
					<div class="map-slider-container rounded border border-primary m-1" style="max-width: 100%; overflow: hidden; position: relative;">
						<div id="map-slider" class="map-slider" style="display: flex; transition: transform 0.3s ease;">
							@foreach($pendingWork as $item)
								@php
									$bookingInfo = $item->bookingInfo ?? null;
								@endphp						
								@if($bookingInfo && $bookingInfo->from_time)
									@php
										$bookingTime = \Carbon\Carbon::parse($bookingInfo->from_time);
										
										$diffMinutes = now()->diffInMinutes($bookingTime, false);
										$hours = floor($diffMinutes / 60);
										$minutes = $diffMinutes % 60;
						
										$formattedTime = $bookingTime->format('g:i a');
										$formattedDifference = ($hours > 0 ? "{$hours}h " : "") . "{$minutes} min";
									@endphp

									<div class="map-card" style="min-width: 100%;">
										<div class="card-body p-0">
											<h5 class="text-center pt-1 pb-0">#WO-{{$item->id}}</h5>

											<div style="height: 150px;" class="border mb-2">
												<div id="map-{{ $item->id }}" 
													class="dynamic-map" 
													data-address="{{ $item->service_location }}" 
													style="height: 100%; width: 100%; border: 1px solid #ccc;">
												</div>
											</div>

											<div class="p-2">
												<div class="row justify-content-between">
													<div class="col-8">
														<p class="mb-0">
															Appointment: 
															<strong>({{ date('M-d, Y', strtotime($bookingInfo->from_date)) }})</strong>
														</p>
														<strong>{{ $formattedTime }}</strong>
													</div>
													<div class="col-4 text-right">
														<p class="mb-0">Remaining:</p>
														<strong>{{ $formattedDifference }}</strong>
													</div>
												</div>
											
												<div class="text-center my-2 p-1 border rounded bg-light">
													<strong>
														{{ implode(', ', App\Models\ServiceGroups::whereIn('id', json_decode($item->service_group))->pluck('name')->toArray()) }}
													</strong>
												</div>
											
												<p class="mb-1"><strong>Location:</strong> {{ $item->service_location }}</p>
											
												<p class="mb-1">
													<strong>Vehicle:</strong>
													@php
														$vehicle = App\Models\Vehicle::whereIn('id', json_decode($item->vehicle))->first();															
														echo $vehicle?->vehicle_makes?->make_name .' ['. $vehicle?->vehicle_models?->model_name .' - '. $vehicle?->rego .']';
													@endphp
												</p>
											
												<p class="mb-1"><strong>Customer:</strong> {{ $item->client?->full_name }}</p>
											
												<p class="mb-2">
													<strong>Contact No:</strong>
													+{{ preg_replace('/[^0-9]/', '', $item->client?->ccm) }}{{ $item->client?->phone_number }}
												</p>												
												<a href="/workorder/details/{{ $item->id }}" class="btn btn-info w-100 py-1">View</a>
											</div>												
										</div>
									</div>
								@endif
							@endforeach
						</div>
						
						<button id="prev-btn" class="slider-nav">
							<i class="fas fa-chevron-left"></i>
						</button>
						<button id="next-btn" class="slider-nav">
							<i class="fas fa-chevron-right"></i>
						</button>
					</div>
				</div>
			@endif
		</div>

		<div class="col-lg-9">
			<div class="row row-sm">
				<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12 ">
					<div class="card overflow-hidden sales-card bg-primary-gradient pb-3">
						<div class="pl-3 pt-3 pr-5 pb-2  d-flex justify-content-between">
							<div class="counter-icon bg-white">
								<i class="icon-paypal " style="color:blue"></i>
							</div>

							<div>
								<div>
									<h6 class="mb-3 tx-12 text-white">Today Payments</h6>
                                  
									<div>
										<h4 class="tx-20 font-weight-bold mb-1 text-white">AED
											
										{{$result['todaypayments']}}
										</h4>

									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
					<div class="card overflow-hidden sales-card bg-warning-gradient pb-3">
						<div class="pl-3 pt-3 pr-5 pb-2  d-flex justify-content-between">
							<div class="counter-icon bg-white">
								<i class="icon-rocket text-warning"></i>
							</div>

							<div>
								<div>
									<h6 class="mb-3 tx-12 text-white">Pending Requests </h6>

									<div>
										<h4 class="tx-20 font-weight-bold mb-1 text-white">
											{{ App\Models\Workorder::where('status', 'Pending')->whereJsonContains('technician', (string)auth()->id())->whereDate('allocation_date', \Carbon\Carbon::today())->count() }}
										</h4>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
					<div class="card overflow-hidden sales-card bg-success pb-3">
						<div class="pl-3 pt-3 pr-5 pb-2  d-flex justify-content-between">
							<div class="counter-icon bg-white">
								<i class="icon-layers text-success"></i>
							</div>


							<div>

								<h6 class="mb-3 tx-12 text-white">My Inventory/Stock</h6>

								<div>

									<h4 class="tx-20 font-weight-bold mb-1 text-white"> {{\Auth::user()->warehouse?->items?->sum('onhand')}} </h4>

								</div>
							</div>
						</div>


					</div>
				</div>
			</div>
			<!-- <div class="card">
				<div class="card-body">
					<div class="tabs-menu ">

						<ul class="nav nav-tabs profile navtab-custom panel-tabs">
							<li class="active">
								<a href="#home" data-toggle="tab" aria-expanded="true"> <span class="visible-xs"><i
											class="las la-user-circle tx-16 mr-1"></i></span> <span class="hidden-xs">ABOUT
										ME</span> </a>
							</li>
							<li class="">
								<a href="#profile" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i
											class="las la-images tx-15 mr-1"></i></span> <span
										class="hidden-xs">GALLERY</span> </a>
							</li>
							<li class="">
								<a href="#settings" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i
											class="las la-cog tx-16 mr-1"></i></span> <span
										class="hidden-xs">SETTINGS</span> </a>
							</li>
						</ul>
					</div>
					<div class="tab-content border-left border-bottom border-right border-top-0 p-4">
						<div class="tab-pane active" id="home">
							<h4 class="tx-15 text-uppercase mb-3">BIOdata</h4>
							<p class="m-b-5">Hi I'm Petey Cruiser,has been the industry's standard dummy text ever since the
								1500s, when an unknown printer took a galley of type. Donec pede justo, fringilla vel,
								aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae,
								justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus
								elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu,
								consequat vitae, eleifend ac, enim.</p>
							<div class="m-t-30">
								<h4 class="tx-15 text-uppercase mt-3">Experience</h4>
								<div class=" p-t-10">
									<h5 class="text-primary m-b-5 tx-14">Lead designer / Developer</h5>
									<p class="">websitename.com</p>
									<p><b>2010-2015</b></p>
									<p class="text-muted tx-13 m-b-0">Lorem Ipsum is simply dummy text of the printing and
										typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
										since the 1500s, when an unknown printer took a galley of type and scrambled it to
										make a type specimen book.</p>
								</div>
								<hr>
								<div class="">
									<h5 class="text-primary m-b-5 tx-14">Senior Graphic Designer</h5>
									<p class="">coderthemes.com</p>
									<p><b>2007-2009</b></p>
									<p class="text-muted tx-13 mb-0">Lorem Ipsum is simply dummy text of the printing and
										typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
										since the 1500s, when an unknown printer took a galley of type and scrambled it to
										make a type specimen book.</p>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="profile">
							<div class="row">
								<div class="col-sm-4">
									<div class="border p-1 card thumb">
										<a href="#" class="image-popup" title="Screenshot-2"> <img
												src="{{URL::asset('assets/img/photos/7.jpg')}}" class="thumb-img"
												alt="work-thumbnail"> </a>
										<h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
										<div class="ga-border"></div>
										<p class="text-muted text-center"><small>Photography</small></p>
									</div>
								</div>
								<div class="col-sm-4">
									<div class=" border p-1 card thumb">
										<a href="#" class="image-popup" title="Screenshot-2"> <img
												src="{{URL::asset('assets/img/photos/8.jpg')}}" class="thumb-img"
												alt="work-thumbnail"> </a>
										<h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
										<div class="ga-border"></div>
										<p class="text-muted text-center"><small>Photography</small></p>
									</div>
								</div>
								<div class="col-sm-4">
									<div class=" border p-1 card thumb">
										<a href="#" class="image-popup" title="Screenshot-2"> <img
												src="{{URL::asset('assets/img/photos/9.jpg')}}" class="thumb-img"
												alt="work-thumbnail"> </a>
										<h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
										<div class="ga-border"></div>
										<p class="text-muted text-center"><small>Photography</small></p>
									</div>
								</div>
								<div class="col-sm-4">
									<div class=" border p-1 card thumb  mb-xl-0">
										<a href="#" class="image-popup" title="Screenshot-2"> <img
												src="{{URL::asset('assets/img/photos/10.jpg')}}" class="thumb-img"
												alt="work-thumbnail"> </a>
										<h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
										<div class="ga-border"></div>
										<p class="text-muted text-center"><small>Photography</small></p>
									</div>
								</div>
								<div class="col-sm-4">
									<div class=" border p-1 card thumb  mb-xl-0">
										<a href="#" class="image-popup" title="Screenshot-2"> <img
												src="{{URL::asset('assets/img/photos/6.jpg')}}" class="thumb-img"
												alt="work-thumbnail"> </a>
										<h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
										<div class="ga-border"></div>
										<p class="text-muted text-center"><small>Photography</small></p>
									</div>
								</div>
								<div class="col-sm-4">
									<div class=" border p-1 card thumb  mb-xl-0">
										<a href="#" class="image-popup" title="Screenshot-2"> <img
												src="{{URL::asset('assets/img/photos/5.jpg')}}" class="thumb-img"
												alt="work-thumbnail"> </a>
										<h4 class="text-center tx-14 mt-3 mb-0">Gallary Image</h4>
										<div class="ga-border"></div>
										<p class="text-muted text-center"><small>Photography</small></p>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="settings">
							<form role="form">
								<div class="form-group">
									<label for="FullName">Full Name</label>
									<input type="text" value="John Doe" id="FullName" class="form-control">
								</div>
								<div class="form-group">
									<label for="Email">Email</label>
									<input type="email" value="first.last@example.com" id="Email" class="form-control">
								</div>
								<div class="form-group">
									<label for="Username">Username</label>
									<input type="text" value="john" id="Username" class="form-control">
								</div>
								<div class="form-group">
									<label for="Password">Password</label>
									<input type="password" placeholder="6 - 15 Characters" id="Password"
										class="form-control">
								</div>
								<div class="form-group">
									<label for="RePassword">Re-Password</label>
									<input type="password" placeholder="6 - 15 Characters" id="RePassword"
										class="form-control">
								</div>
								<div class="form-group">
									<label for="AboutMe">About Me</label>
									<textarea id="AboutMe"
										class="form-control">Loren gypsum dolor sit mate, consecrate disciplining lit, tied diam nonunion nib modernism tincidunt it Loretta dolor manga Amalia erst volute. Ur wise denim ad minim venial, quid nostrum exercise ration perambulator suspicious cortisol nil it applique ex ea commodore consequent.</textarea>
								</div>
								<button class="btn btn-primary waves-effect waves-light w-md" type="submit">Save</button>
							</form>
						</div>
					</div>
				</div>
			</div> -->
			<div class="card bg-success-transparent">
				<div class="card-header bg-success-transparent py-2">
					<div class="card-title mb-0">Accepted WorkOrder Details</div>
				</div>
				<div class="card-body p-1">
					<div class="table-responsive">
						<table class="table table-bordered mg-b-0 text-md-nowrap">
							<thead>
								<tr>
									<th>ID</th>
									<th>Customer Name</th>
									<th>Type</th>
									<th>Service Group</th>
									<th>Phone</th>
									<th>V Reg</th>
									<th>Technician</th>
									<th>Booking Schedule Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($acceptedworkOrders as $key =>$workorder)
								<tr>
									<td data-order="{{ $key+1 }}">#WO-{{$workorder->id}}</td>
									<td>{{$workorder->client->first_name}}</td>
									<td>{{strtoupper($workorder->client->client_type)}}</td>
									<td>{{App\Models\ServiceGroups::where('id',json_decode($workorder->service_group,true))->first()->name??""}}
									</td>
									<td>+{{preg_replace('/[^0-9]/', '',  $workorder->client->ccm )}}{{$workorder->client->phone_number}}
									</td>
									<td>{{App\Models\Vehicle::where('id',json_decode($workorder->vehicle,true))->first()->rego??''}}
									</td>
									<td>{{App\Models\User::where('id',json_decode($workorder->technician,true))->first()->full_name??''}}
									</td>
									<td>{{App\Models\Booking::where('id',json_decode($workorder->booking,true))->first()->booking_date??''}}
									</td>
									<td>{{$workorder->status}}</td>
									<td>
										@if($workorder->status !== 'canceled')
										<a class="text-primary customModal" data-bs-toggle="tooltip"
											href="/workorder/details/{{ $workorder->id }}">
											<img src="{{URL::asset('assets/img/icons/eye.svg')}}"
												style='width:25px;height:25px;'>
										</a>

										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="card bg-danger-transparent">
				<div class="card-header bg-danger-transparent py-2">
					<div class="card-title mb-0">Pending WorkOrder Details</div>
				</div>
				<div class="card-body p-1">
					<div class="table-responsive">
						<table class="table table-bordered text-md-nowrap mb-0">
							<thead>
								<tr>
									<th>ID</th>
									<th>Customer Name</th>
									<th>Type</th>
									<th>Service Group</th>
									<th>Phone</th>
									<th>V Reg</th>
									<th>Technician</th>
									<th>Booking Schedule Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($pendingworkorder as $workorder)
								<tr>
									<td>#WO-{{$workorder->id}}</td>
									<td>{{$workorder->client->first_name}}</td>
									<td>{{strtoupper($workorder->client->client_type)}}</td>
									<td>{{App\Models\ServiceGroups::where('id',json_decode($workorder->service_group,true))->first()->name??""}}

									<td>+{{ preg_replace('/[^0-9]/', '', $workorder->client?->ccm)}}{{$workorder->client->phone_number}}</td>
									<td>{{App\Models\Vehicle::where('id',json_decode($workorder->vehicle,true))->first()->rego??''}}</td>
									<td>{{App\Models\User::where('id',json_decode($workorder->technician,true))->first()->full_name??''}}</td>
									<td>{{App\Models\Booking::where('id',json_decode($workorder->booking,true))->first()->booking_date??''}}</td>
									<td>{{$workorder->status}}</td>
									<td>
										@if($workorder->status !== 'canceled')
										<a class="text-primary customModal" data-bs-toggle="tooltip"
											href="/workorder/details/{{ $workorder->id }}">
											<img src="{{URL::asset('assets/img/icons/eye.svg')}}"
												style='width:25px;height:25px;'>
										</a>
										@endif
									</td>
								</tr>
								@endforeach

							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
@endsection
@section('js')
	<script async src="https://maps.googleapis.com/maps/api/js?key={{ googleApiKey() }}&loading=async&libraries=places">
	</script>
	
	<script>
		function initMap(lat, long, callback) {
			const latlng = {
				lat: lat,
				lng: long
			};
			const geocoder = new google.maps.Geocoder();
			geocoder.geocode({
				location: latlng
			}, (results, status) => {
				if (status === "OK" && results[0]) {
					callback(results[0].formatted_address);
				} else {
					callback(false);
				}
			});
		}

		document.addEventListener('DOMContentLoaded', function() {

			const checkinBtn = document.getElementById('checkin-button');
			const checkoutBtn = document.getElementById('checkout-button');
			const breakBtn = document.getElementById('break-button');
			const unbreakBtn = document.getElementById('unbreak-button');

			if (checkinBtn) {
				checkinBtn.addEventListener('click', function () {
					if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(function(position) {
							const lat = position.coords.latitude;
							const long = position.coords.longitude;
							initMap(lat, long, function(workingAddress) {
								if (workingAddress) {
									fetch('{{ route('checkin') }}', {
												method: 'POST',
												headers: {
													'Content-Type': 'application/json',
													'X-CSRF-TOKEN': '{{ csrf_token() }}'
												},
												body: JSON.stringify({
													lat: lat,
													long: long,
													working_address: workingAddress
												})
											})
										.then(response => response.json())
										.then(data => {
											document.getElementById('alert-container')
												.innerHTML =
												'<div class="alert alert-success alert-dismissible fade show" role="alert">Check-in Successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
										location.reload();
											})
										.catch(error => {
											document.getElementById('alert-container')
												.innerHTML =
												'<div class="alert alert-danger alert-dismissible fade show" role="alert">Check-in failed. Please try again.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
										});
								} else {
									document.getElementById('alert-container').innerHTML =
										'<div class="alert alert-danger alert-dismissible fade show" role="alert">Geocoding failed. Please try again.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
								}
							});
						});
					} else {
						document.getElementById('alert-container').innerHTML =
							'<div class="alert alert-danger alert-dismissible fade show" role="alert">Geolocation is not supported by this browser.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}
				});
			}

			if (checkoutBtn) {
				checkoutBtn.addEventListener('click', function () {
					fetch('{{ route("checkout") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						}
					})
					.then(response => response.json())
					.then(data => {
						document.getElementById('alert-container').innerHTML =
							'<div class="alert alert-success alert-dismissible fade show" role="alert">You successfully completed the check-out process.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
						location.reload();					
					}).catch(error => {
						document.getElementById('alert-container').innerHTML =
							'<div class="alert alert-danger alert-dismissible fade show" role="alert">Check-out failed. Please try again.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					});
				});
			}

			if (breakBtn) {
				breakBtn.addEventListener('click', function () {
					fetch('{{ route("checkout") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': '{{ csrf_token() }}'
						}
					})
					.then(response => response.json())
					.then(data => {
						document.getElementById('alert-container').innerHTML =
							'<div class="alert alert-success alert-dismissible fade show" role="alert">Break time started!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
						location.reload();
					}).catch(error => {
						document.getElementById('alert-container').innerHTML =
							'<div class="alert alert-danger alert-dismissible fade show" role="alert">Break time initiation failed. Please try again.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					});
				});
			}
			
			document.getElementById('unbreak-button').addEventListener('click', function() {
				document.getElementById('unbreak-button').classList.add('d-none');
				document.getElementById('break-button').classList.remove('d-none');
			});
		});
	</script>

	<script>
		function waitForGoogleMaps(callback) {
			if (typeof google !== 'undefined' && google.maps && google.maps.Geocoder) {
				callback();
			} else {
				setTimeout(() => waitForGoogleMaps(callback), 100);
			}
		}

		function initializeDynamicMaps() {
			const maps = document.querySelectorAll('.dynamic-map');
			const geocoder = new google.maps.Geocoder();

			maps.forEach((el) => {
				const address = el.dataset.address;

				geocoder.geocode({ address }, function(results, status) {
					if (status === 'OK' && results[0]) {
						const map = new google.maps.Map(el, {
							center: results[0].geometry.location,
							zoom: 15
						});

						new google.maps.Marker({
							map: map,
							position: results[0].geometry.location
						});
					} else {
						console.warn('Geocoding failed for:', address, status);
					}
				});
			});
		}

		document.addEventListener('DOMContentLoaded', () => {
			let currentIndex = 0;
			const slider = document.getElementById('map-slider');
			const totalSlides = slider.children.length;

			document.getElementById('prev-btn').addEventListener('click', () => {
				if (currentIndex > 0) {
					currentIndex--;
					slider.style.transform = `translateX(-${currentIndex * 100}%)`;
				}
			});

			document.getElementById('next-btn').addEventListener('click', () => {
				if (currentIndex < totalSlides - 1) {
					currentIndex++;
					slider.style.transform = `translateX(-${currentIndex * 100}%)`;
				}
			});
		});

		waitForGoogleMaps(initializeDynamicMaps);
	</script>

	<script type="module">
		import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
		import { getDatabase, ref, onChildAdded, remove } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js';

		document.addEventListener('DOMContentLoaded', () => {
			// Firebase configuration
			const firebaseConfig = {
				databaseURL: '{{ config("firebase.database_url") }}'
			};

			// Ensure Firebase URL exists
			if (!firebaseConfig.databaseURL) {
				console.error("❌ Firebase configuration missing or invalid.");
				return;
			}
			

			// Initialize Firebase
			const app = initializeApp(firebaseConfig);
			const database = getDatabase(app);

			// Reference to alerts
			const alertsRef = ref(database, 'alerts');
			
			loadTasks();
			loadQuotation();
			// Listen for new alerts
			onChildAdded(alertsRef, (snapshot) => {
				const alert = snapshot.val();
				const alertKey = snapshot.key;
				
				if (alert.type === 'technican_alert' && alert.recipient_id == "{{ auth()->id() }}") {
					
					loadTasks();
					loadQuotation();
					// Remove alert from Firebase once shown
					remove(ref(database, `alerts/${alertKey}`))
						.catch((err) => console.error("Error removing alert:", err));
				}
			});

			function loadTasks() {
				fetch('/booking-alert')
				.then(response => {
					if (!response.ok) throw new Error("Network error loading tasks");
					return response.json();
				})
				.then(data => {
					const { technicianExist, workOrders } = data;
					const tasksList = document.getElementById('tasksListDashboard');

					// Clear current tasks
					tasksList.innerHTML = '';

					if (workOrders.length > 0) {
						// Update task count

						workOrders.forEach(task => {
							tasksList.appendChild(createTaskElement(task));
						});
					}
				})
				.catch(error => console.error('❌ Failed to load tasks:', error));
			}
			function loadQuotation() {
				fetch('/quatation-accept-alert')
				.then(response => {
					if (!response.ok) throw new Error("Network error loading tasks");
					return response.json();
				})
				.then(data => {
					const { quotationExist, acceptedQuotations } = data;
					const quotationList = document.getElementById('quotationList');

					// Clear current tasks
					quotationList.innerHTML = '';

					if (acceptedQuotations.length > 0) {
						// Update task count

						acceptedQuotations.forEach(task => {
							quotationList.appendChild(createQuotationElement(task));
						});
					}
				})
				.catch(error => console.error('❌ Failed to load quotation:', error));
			}


			function createTaskElement(task) {
				const taskItem = document.createElement('a');
				taskItem.href = `/workorder/details/${task.id}`;
				taskItem.className = 'btn btn-warning d-block text-left mb-2 text-dark';
				taskItem.innerHTML = `
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<strong>Attention!</strong> You have a pending work order 
							<strong>(ID: ${task.id})</strong>. Click to view details
						</div>
					</div>
				`;
				return taskItem;
			}

			function createQuotationElement(task) {
				const taskItem = document.createElement('a');
				taskItem.href = `/workorder/details/${task.workorder_id}`;
				taskItem.className = 'btn btn-warning d-block text-left mb-2 text-dark';
				taskItem.innerHTML = `
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<strong>Attention!</strong> Your Quotation has been approved, For work order <strong>(ID: ${task.workorder_id})</strong>. Click to view details
						</div>
					</div>
				`;
				return taskItem;
			}
		});
	</script>

@endsection