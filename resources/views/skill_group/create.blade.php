<div class="modal-body">
    <form method="POST" action="{{ route('skill-group.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <!-- Group Name -->
            <div class="col-md-12 mb-3">
                <label for="group_name" class="form-label">Group Name</label>
                <input type="text" class="form-control" name="group_name" id="group_name" placeholder="Enter Group Name"
                    required>
            </div>

            <!-- Skills (Multiple Select with Select2) -->
            <div class="col-md-12 mb-3">
                <label for="skill_id" class="form-label">Skills</label>
                <select class="selectize" id="skill"  name="skill_id[]" multiple="multiple" required>
                    @foreach($skills as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Vehicle Make -->
            <div class="col-md-12 mb-3">
                <label for="v_make">Vehicle Make</label>
                <select class="form-control" name="vehicle_make" id="v_make">
                   
                    @foreach ($vm as $id => $make)
                    <option value="{{ $id }}">{{ $make }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Vehicle Model -->
            <div class="col-md-12 mb-3">
                <label for="v_model">Vehicle Model</label>
                <select class="form-control" name="vehicle_model" id="v_model">
                    <option value="">Select Vehicle Model</option>
                </select>
            </div>

            <div class="col-md-6 my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>



<script>

$(".selectize").selectize({
  plugins: ["remove_button"],
  delimiter: ",",
  persist: false,
  create: function (input) {
    return {
        value: input,
        text: input,
    };
  },
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function() {

  
    // Populate Vehicle Models based on selected Make
    $('#v_make').on('change', function() {
        let makeId = $(this).val();

        if (makeId) {
            $.ajax({
                url: "{{ route('get.vehicle.models', ':makeId') }}".replace(':makeId',
                    makeId), // Dynamic URL
                type: "GET",
                dataType: "json",
                success: function(models) {
                    // Clear and populate the Vehicle Model dropdown
                    $('#v_model').empty().append(
                        '<option value="">Select Vehicle Model</option>');

                    models.forEach(function(model) {
                        $('#v_model').append('<option value="' + model.id + '">' +
                            model.model_name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching vehicle models:', error);
                }
            });
        } else {
            $('#v_model').empty().append('<option value="">Select Vehicle Model</option>');
        }

        // Clear engine specification input if make changes
        $('#engine_spec').val('');
    });


});
</script>