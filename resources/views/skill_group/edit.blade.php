<div class="modal-body">
    <form method="POST" action="{{ route('skill-group.update', $group->id) }}" accept-charset="UTF-8"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Group Name -->
            <div class="form-group col-md-12 mb-3">
                <label for="group_name" class="form-label">Group Name</label>
                <input class="form-control" placeholder="Enter Group Name" name="group_name" type="text"
                    value="{{ old('group_name', $group->group_name) }}" id="group_name">
            </div>

            <!-- Skills (Multiple Select with Select2) -->
            <div class="col-md-12 mb-3">
                <label for="skill_id" class="form-label">Skills</label>
                <select class="selectize" id="skill" multiple name="skill_id[]">
                    @foreach($skills as $skill)
                    <option value="{{ $skill->id }}"
                        {{ in_array($skill->id, old('skill_id', $selectedSkillIds)) ? 'selected' : '' }}>
                        {{ $skill->skill_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Vehicle Make -->
            <div class="col-md-12 mb-3">
                <label for="vehicle_make" class="form-label">Vehicle Make</label>
                <select class="form-control" name="vehicle_make" id="v_make">
                    @foreach($vm as $id => $make_name)
                    <option value="{{ $id }}" {{ $id==$group->vehicle_make_id  ? 'selected' : '' }}>
                        {{ $make_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Vehicle Model -->
            <div class="col-md-12 mb-3">
                <label for="vehicle_model" class="form-label">Vehicle Model</label>
                <select class="form-control" name="vehicle_model" id="v_model">
                    @foreach($vmod as $id => $model_name)
                    <option value="{{ $id }}"
                    {{ $id==$group->vehicle_model_id  ? 'selected' : '' }}>
                        {{ $model_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <input class="btn btn-primary btn-rounded" type="submit" value="Update">
        </div>
    </form>
</div>


<!-- Initialize Select2 -->
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