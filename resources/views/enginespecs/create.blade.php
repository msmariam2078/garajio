<div class="modal-body">
    <form method="POST" action="{{ route('enginespecification.store') }}" accept-charset="UTF-8"
        enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <!-- Specs Title -->
            <div class="col-md-12 mb-3">
                <label for="enginespecs" class="form-label">Specs Title</label>
                <input type="text" class="form-control" name="enginespecs" id="enginespecs"
                    placeholder="Enter Engine Specs">
            </div>

            <!-- Make -->
            <div class="col-md-12 mb-3">
                <label for="make_id" class="form-label">Make</label>
                <select class="form-control " name="make_id" id="make_id">
                    <option value="" disabled selected>Select Make</option>
                    @foreach($vmake as $make)
                    <option value="{{ $make->id }}">{{ $make->make_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Model -->
            <div class="col-md-12 mb-3">
                <label for="model_id" class="form-label">Model</label>
                <select class="form-control" name="model_id" id="model_id">
                    <option value="" disabled selected>Select Model</option>
                    @foreach($vmodel as $model)
                    <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
<script>
    
$(document).ready(function() {

  
// Populate Vehicle Models based on selected Make
$('#make_id').on('change', function() {
    let makeId = $(this).val();

    if (makeId) {
        $.ajax({
            url: "{{ route('get.vehicle.models', ':makeId') }}".replace(':makeId',
                makeId), // Dynamic URL
            type: "GET",
            dataType: "json",
            success: function(models) {
                // Clear and populate the Vehicle Model dropdown
                $('#model_id').empty().append(
                    '<option value="">Select Vehicle Model</option>');

                models.forEach(function(model) {
                    $('#model_id').append('<option value="' + model.id + '">' +
                        model.model_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching vehicle models:', error);
            }
        });
    } else {
        $('#model_id').empty().append('<option value="">Select Vehicle Model</option>');
    }

    
});


});
</script>