<div class="modal-body">
    <form method="POST" action="{{ route('enginespecification.update', $es->id) }}" accept-charset="UTF-8"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Engine Specs Field -->
            <div class="form-group col-md-12">
                <label for="enginespecs" class="form-label">Engine Specs</label>
                <input class="form-control" placeholder="Enter Engine Specs" name="enginespecs" type="text"
                    value="{{ old('enginespecs', $es->enginespecs) }}" id="enginespecs">
            </div>

            <!-- Vehicle Make Dropdown -->
            <div class="form-group col-md-6">
                <label for="make_id" class="form-label">Vehicle Make</label>
                <select class="form-control" name="make_id" id="make_id">
                    @foreach($vmake as $make)
                    <option value="{{ $make->id }}" {{ $make->id == $es->make_id ? 'selected' : '' }}>
                        {{ $make->make_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Vehicle Model Dropdown -->
            <div class="form-group col-md-6">
                <label for="model_id" class="form-label">Vehicle Model</label>
                <select class="form-control" name="model_id" id="model_id">
                    @foreach($vmodel as $model)
                    <option value="{{ $model->id }}" {{ $model->id == $es->model_id ? 'selected' : '' }}>
                        {{ $model->model_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="modal-footer">
            <input class="btn btn-primary btn-rounded" type="submit" value="Update">
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