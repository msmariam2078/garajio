<form method="POST" action="{{ route('service-group.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="customModal">
        <div class="row">
            <div class="form-group col-md-12 mb-4">
                <label for="name" class="form-label mb-3">Service Group Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="v_make">Vehicle Make</label>
                <select class="form-control" name="vm_id" id="v_make">
                    @foreach ($vm as $id => $make)
                    <option value="{{ $id }}">{{ $make }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label for="v_model">Vehicle Model</label>
                <select class="form-control" name="vmod_id" id="v_model">
                    @foreach ($vmod as $id => $model)
                    <option value="{{ $id }}">{{ $model }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label for="service">Select Service</label>
                <select class="selectize" name="service_master[]" id="service" required multiple>
                    @foreach ($servicemaster as $item)
                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

<script>
$('#close').click(function() {
    $("#customModal").modal("hide");
});
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
<script>
$(document).ready(function() {

    $('#v_make').on('change', function() {
        let makeId = $(this).val();

        if (makeId) {
            $.ajax({
                url: "{{ route('get.vehicle.models', ':makeId') }}".replace(':makeId',
                    makeId),
                type: "GET",
                dataType: "json",
                success: function(models) {

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


        $('#engine_spec').val('');
    });


});
</script>