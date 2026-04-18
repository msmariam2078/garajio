<div class="modal-body">
    <form method="POST" action="{{ route('vehiclemodel.update', $vmodel->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-3">


            <div class="col-md-12 mb-3">
                <label for="model_name" class="form-label">Model Name</label>
                <input type="text" class="form-control" name="model_name" id="model_name"
                    value="{{ $vmodel->model_name }}">
            </div>

            <div class="col-md-12 mb-3">
                <label for="make_id" class="form-label">Vehicle Make</label>
                <select class="form-control" name="make_id" id="make_id">
                    <option value="" disabled>Select Vehicle Make</option>
                    @foreach ($vmakes as $make)
                    <option value="{{ $make->id }}" {{ $vmodel->make_id == $make->id ? 'selected' : '' }}>
                        {{ $make->make_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>

</div>
