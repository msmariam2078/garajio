<div class="col-xl-6">
	<div class="card custom-card">
		<div class="card-body py-3">
			<div class="d-flex justify-content-between align-items-center">
				<h5 class="card-title">Vehicles Details</h5>
				<button class="btn btn-success float-right py-1" id="vehiclesDetailsEdit">Update</button>
			</div>
		</div> 

		<div class="card-body pb-2">
			<div class="row">
				{{-- @dd($vehicles); --}}
				@foreach ($vehicles as $key => $vehicle)
					<div class="col-6 mb-3">
						<label class="form-label me-3 text-end">{{ __('Vehicle ID') }}:</label>
						<input type="text" id="equipment_id" class="form-control rounded-0" value="{{ $vehicle->id }}"
							readonly>
					</div>
					<div class="col-6 mb-3">
						<label class="form-label me-3 text-end">{{ __('Registration Number') }}:</label>
						<input type="text" id="rego" class="form-control rounded-0" value="{{ @$vehicle->rego }}" {{@$readonly}} {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
					</div>
					<div class="col-6 mb-3">
						<label class="form-label me-3 text-end">{{ __('Make') }}:</label>
						{{-- <input type="text" class="form-control rounded-0" value="{{ @$vehicle->vehicle_makes?->make_name }}"> --}}

						<select class="form-control" name="v_make" id="v_make" required {{Auth::user()->type != 'technician' ? '' : 'disabled'}} {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'disabled' : '' }}>
							@foreach ($vm as $id => $make)
								<option value="{{ $id }}"
									{{ $vehicle->vehicle_makes?->id == $id ? 'selected' : '' }}>{{ $make }}
								</option>
							@endforeach
						</select>
					</div>
					<input type="hidden" id="selectedVehicleId" value="{{ $vehicle->id }}"
						name="vehicle_id" />

					<div class="col-6 mb-3">
						<label class="form-label">{{ __('Model') }}:</label>
						{{-- <input type="text" class="form-control" id="v_make" value="{{ @$vehicle->vehicle_models?->model_name }}"> --}}

						<select class="form-control" name="v_model" id="v_model" required {{Auth::user()->type != 'technician' ? '' : 'disabled'}} {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'disabled' : '' }}>
							@foreach ($vmod as $id => $make)
								<option value="{{ $id }}"
									{{ $vehicle->vehicle_models?->id == $id ? 'selected' : '' }}>
									{{ $make }}</option>
							@endforeach
						</select>
					</div>
					
					<div class="col-4">
						<label class="form-label">{{ __('Year Series') }}:</label>
						<select id="model_series" class="form-control" {{Auth::user()->type != 'technician' ? '' : 'disabled'}} {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'disabled' : '' }}>
							<option value="0000">N/A<option>
								@for ($year = 1900; $year <= 2099; $year++)
									<option value="{{ $year }}"
										{{ $vehicle->model_series == $year ? 'selected' : '' }}>{{ $year }}
									</option>
								@endfor
						</select>
					</div>
					<div class="col-4">
						<label class="form-label">{{ __('Vin') }}:</label>
						<input type="text" id="vin" class="form-control" value="{{ @$vehicle->vin }}" {{$readonly}} {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
					</div>
					<div class="col-4">
						<label class="form-label">{{ __('Odometer') }}:</label>
						<input type="text" id="Odometer" class="form-control" value="{{ @$vehicle->odometer }}" {{$readonly}} {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'readonly' : '' }}>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>