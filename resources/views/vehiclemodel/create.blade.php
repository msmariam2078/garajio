<div class="modal-body">
    <form method="POST" action="{{ route('vehiclemodel.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <div class="row">
			<div class="col-md-12 mb-3">
				<label for="make_id" class="form-label">Vehicle Make</label>
				<select class="form-control" name="make_id" id="make_id">
					<option value="" disabled selected>Select Vehicle Make</option>
					@foreach ($vmake as $make)
						<option value="{{ $make->id }}">{{ $make->make_name }}</option>
					@endforeach
				</select>
			</div>           
            <div class="col-md-12 mb-3">
                <label for="model_name" class="form-label">Model Name</label>
                <input type="text" class="form-control" name="model_name" id="model_name" placeholder="Enter Model Name">
            </div>
            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
