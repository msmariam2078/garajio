<style>
.custom {
    width: 800px;
    border-radius: 20px;
    padding: 20px;
}
</style>
<script>
</script>


<div class="modal-body ms-5">

    <form method="POST" action="{{ route('service-group.update', $servicegroups->id) }}" accept-charset="UTF-8"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')



        <div class="row">
            <div class="form-group col-md-12 mb-4">
                <label for="name" class="form-label mb-3">Service Group Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Group Name"
                    value="{{ old('name', $servicegroups->name) }}">
            </div>
            <div class="form-group col-md-12 mb-4">
                {{ Form::label('vm_id', __('Vehicle Make'), ['class' => 'form-label mb-3']) }}

                <select class="form-control" id="v_make" name="vm_id">
                    @foreach($vm as $id=>$value)
                    <option value={{$id}} {{$servicegroups->vm_id==$id? 'selected': ''}}>{{$value}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12 mb-4">

                {{ Form::label('vmod_id', __('Vehicle Model'), ['class' => 'form-label mb-3']) }}
                <select class="form-control" id="v_model" name="vmod_id">
                    @foreach($vmod as $id=>$value)
                    <option value={{$id}} {{$servicegroups->vmod_id==$id? 'selected': ''}}>{{$value}}
                    </option>
                    @endforeach
                </select>

            </div>

            <div class="form-group col-md-12 z-3">
                {{ Form::label('service_master', __('Select Services'), ['class' => 'form-label ']) }}
                {!! Form::select('service_master[]', $servicemaster->pluck('title', 'id'),
                old('service_master', $servicegroups->service_master_id ? explode(',',
                $servicegroups->service_master_id) : []), [
                'class' => 'selectize',
                'multiple' => 'multiple',
                'required' => 'required'
                ]) !!}
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>

        </div>
    </form>
</div>










<script>
$(".selectize").selectize({
    plugins: ["remove_button"],
    delimiter: ",",
    persist: false,
    create: function(input) {
        return {
            value: input,
            text: input,
        };
    },
});
</script>
<script>
$(document).ready(function() {
    function fetchVehicleModels(makeId, selectedModelId = null) {
        if (makeId) {
            $.ajax({
                url: "{{ route('get.vehicle.models', ':makeId') }}".replace(':makeId', makeId),
                type: "GET",
                dataType: "json",
                success: function(models) {
                    $('#v_model').empty().append('<option value="">Select Vehicle Model</option>');

                    models.forEach(function(model) {
                        let selected = selectedModelId == model.id ? 'selected' : '';
                        $('#v_model').append('<option value="' + model.id + '" ' +
                            selected + '>' + model.model_name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching vehicle models:', error);
                }
            });
        } else {
            $('#v_model').empty().append('<option value="">Select Vehicle Model</option>');
        }
    }

    // Fetch models when vehicle make changes
    $('#v_make').on('change', function() {
        let makeId = $(this).val();
        fetchVehicleModels(makeId);
    });

    // Fetch models on page load if a vehicle make is preselected
    let preselectedMakeId = $('#v_make').val();
    let preselectedModelId = "{{ $servicegroups->vmod_id ?? '' }}"; // Ensure this is correctly set
    if (preselectedMakeId) {
        fetchVehicleModels(preselectedMakeId, preselectedModelId);
    }
});
</script>