<div class="modal-body">
    <form method="POST" action="{{ route('servicepartwithvehicle.store') }}" accept-charset="UTF-8" enctype="multipart/form-data" id="workordermodal">
        @csrf

        <div class="form-group">
            <label for="service_part_id">Service Part</label>
            <select class="selectize" name="service_part_id" id="service_part_id" required>
                <option value="">{{ __('Select Service Part') }}</option>
                @foreach ($serviceParts as $part)
                    <option value="{{ $part->id }}">{{ $part->item_no .' ('. $part->product_name .')' }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="v_make">Vehicle Make</label>
            <select class="selectize" name="v_make" id="v_make" required >
                @foreach ($vm as $id => $make)
                    <option value="{{ $id }}">{{ $make }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="v_model">Vehicle Model</label>
            <select class="form-control" name="v_model" id="v_model" required >
                @foreach ($vmod as $id => $make)
                    <option value="{{ $id }}">{{ $make }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            {{ Form::label('model_series', 'Year of Manufacture', ['class' => 'form-label']) }}
            <select name="yom" class="form-control">
                <option value="N/A">N/A</option>
                @for ($year = 1900; $year <= 2099; $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label for="engine_spec">Engine Specification</label>
            <select class="form-control" name="engine_spec" id="engine_spec">
                <option value="">Select Vehicle Model</option>
                @foreach ($es as $item)
                    <option value="{{ $item->enginespecs }}">{{ $item->enginespecs }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" id="status" required>
                <option value="">{{ __('Select Status') }}</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary col-md-4">Submit</button>
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
        });

        $('#v_model').on('change', function() {
            let makeId = $('#v_make').val();
            let modelId = $(this).val();

            if (makeId && modelId) {
                $.ajax({
                    url: "{{ route('get.engine.specification') }}",
                    type: "GET",
                    data: {
                        make_id: makeId,
                        model_id: modelId
                    },
                    dataType: "json",
                    success: function(specifications) {
                        if (specifications) {
                            $('#engine_spec').empty().append(
                                '<option value="">Select engine specification</option>');
                            specifications.forEach(function(specification) {
                                $('#engine_spec').append('<option value="' +
                                    specification.enginespecs + '">' +
                                    specification.enginespecs + '</option>');
                            });
                        } else {
                            $('#engine_spec').val('No specification available');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching engine specification:', error);
                        $('#engine_spec').val('Error fetching specification');
                    }
                });
            } else {
                $('#engine_spec').val('');
            }
        });
    });
</script>
