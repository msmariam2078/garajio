<div class="modal-body">
    <form method="POST" action="{{ route('servicepartwithvehicle.update', $servicePartWithVS->id) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="service_part_id">Service Part</label>
            <select class="" name="service_part_id" id="service_part_id" required>
                @foreach ($serviceParts as $servicePart)
                <option value="{{ $servicePart->id }}"
                    {{ $servicePartWithVS->service_part_id == $servicePart->id ? 'selected' : '' }}>
                    {{ $servicePart->product_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="v_make">Vehicle Make</label>
            <select class="" name="v_make" id="v_make" required>
                @foreach ($vehicleMakes as $vehicleMake)
                <option value="{{ $vehicleMake->id }}"
                    {{ $servicePartWithVS->v_make == $vehicleMake->id ? 'selected' : '' }}>
                    {{ $vehicleMake->make_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="v_model">Vehicle Model</label>
            <select class="form-control" name="v_model" id="v_model" required>
                @foreach ($vehicleModels as $vehicleModel)
                <option value="{{ $vehicleModel->id }}"
                    {{ $servicePartWithVS->v_model == $vehicleModel->id ? 'selected' : '' }}>
                    {{ $vehicleModel->model_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            {{ Form::label('yom', 'Year of Manufacture (YOM)', ['class' => 'form-label']) }}
            {{ Form::select('yom', ['N/A' => 'N/A'] + array_combine(range(1900, 2099), range(1900, 2099)), 
        $servicePartWithVS->yom, ['class' => 'form-control', 'required']) }}
        </div>


        <div class="form-group">
            <label for="engine_spec">Engine Specification</label>
			<select class="form-control" name="engine_spec" id="v_make" required >
				@foreach ($es as $item)
				<option value="{{ $item->enginespecs }}" {{ $item->enginespecs == $servicePartWithVS->engine_spec ? 'selected' : '' }}>
					{{ $item->enginespecs }}
				</option>
				@endforeach
			</select>
        </div>
		
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" id="status">
                <option value="1" {{ $servicePartWithVS->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $servicePartWithVS->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
<script>
    
    $(document).ready(function() {
        $('#v_make').selectize();
        
        $('#service_part_id').selectize();});
       
    $(document).ready(function() {
        $('#v_make').on('change', function() {
            let makeId = $(this).val();

            if (makeId) {
                $.ajax({
                    url: "/get-vehicle-models/" + makeId,
                    type: "GET",
             
                    dataType: "json",
                    success: function(models) {
                        $('#v_model').empty().append(
                            '<option value="">Select Vehicle Model</option>');

                        models.forEach(function(model) {
                            $('#v_model').append('<option value="' + model.id +
                                '">' + model.model_name + '</option>');
                        });
                        $('#v_model')[0].selectize.trigger( "change" );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching vehicle models:', error);
                    }
                });
            } else {
                $('#v_model').empty().append('<option value="">Select Vehicle Model</option>');
            }
            $('#engine_spec').empty().append('<option value="">Select Vehicle Model First</option>');
        });}); 
        
    </script>