{{ Form::model($warehouse, ['route' => ['warehouse.update', $warehouse->id], 'method' => 'post']) }}
@method('PUT')
<div class="modal-body ms-5">

    <div class="row">
        <div class="form-group col-md-12 mb-4">
            {{ Form::label('name', __('Name'), ['class' => 'form-label mb-3']) }}
            {{ Form::text('name', $warehouse->name, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12 mb-4 col-sm-12">
            <label for="description" class="form-label mb-3">Description</label>
            <textarea class="form-control" rows="2" name="description" cols="50" id="description"
                required>{{ $warehouse->description }}</textarea>
        </div>
        <div class='col-md-12 my-3'>
            {{ Form::label('Fleet', __('Fleet'), ['class' => 'form-label']) }}
            <label class="custom-switch">

                <input type="checkbox" class="toggle-status" id="type-switch1" name="type"
                    value="warehouse" {{ $warehouse->technicians && $warehouse->type ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
        </div>

        <div class="form-group col-md-6 " id='fleet1' style='display:none;'>
            {{ Form::label('fleet_type', __('Fleet Type'), ['class' => 'form-label']) }}

            <select name="fleet_type">
                <option value=""></option>


                <option value="two_wheeler" {{ $warehouse->type = 'two_wheeler' ? 'selected' : '' }}>Two Wheeler</option>
                <option value="four_wheeler" {{ $warehouse->type = 'four_wheeler' ? 'selected' : '' }}>Four Wheeler
                </option>


            </select>

        </div>
        <div class="form-group col-md-6 " id='tech1' style='display:none;'>
            {{ Form::label('technician', __('Technician'), ['class' => 'form-label']) }}
            <select name="technicians">
                <option value=""></option>
                @foreach ($technicians as $id => $name)
                    <option value="{{ $id }}" {{ $id == $warehouse->technicians ? 'selected' : '' }}>
                        {{ $name }}</option>
                @endforeach
            </select>

        </div>

        <div class="form-group col-md-12 mb-4">
            {{ Form::label('address', __('Address'), ['class' => 'form-label mb-3']) }}

            {{ Form::textarea('address', $warehouse->address, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter address')]) }}
        </div>
        <div class="form-group col-md-6 mb-3">
            {{ Form::label('city', __('City'), ['class' => 'form-label mb-3']) }}
            {{ Form::text('city', $warehouse->city, ['class' => 'form-control', 'placeholder' => __('Enter city')]) }}
        </div>
        <div class="form-group col-md-6 mb-3">
            {{ Form::label('state', __('State'), ['class' => 'form-label mb-3']) }}
            {{ Form::text('state', $warehouse->state, ['class' => 'form-control', 'placeholder' => __('Enter state')]) }}
        </div>
        <div class="form-group col-md-6 mb-3">
            {{ Form::label('country', __('Country'), ['class' => 'form-label mb-3']) }}
            {{ Form::text('country', $warehouse->country, ['class' => 'form-control', 'placeholder' => __('Enter country')]) }}
        </div>
        <div class="form-group col-md-6 mb-3">
            {{ Form::label('zip_code', __('Post Code'), ['class' => 'form-label mb-3']) }}
            {{ Form::text('zip_code', $warehouse->zip_code, ['class' => 'form-control', 'placeholder' => __('Enter post code')]) }}
        </div>
    </div>








</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    {{ Form::submit(__('Edit'), ['class' => 'btn btn-primary ml-10']) }}
</div>
{{ Form::close() }}



<script>
    $(document).ready(function() {
        $("select").selectize();
        if ($('#type-switch1').is(':checked')) {
            $('#fleet1').show();
            $('#tech1').show();

        } else {
            $('#fleet1').hide();
            $('#tech1').hide();
        }
        $('#type-switch1').change(function() {
            if ($('#type-switch1').is(':checked')) {
                $('#fleet1').show();
                $('#tech1').show();

            } else {
                $('#fleet1').hide();
                $('#tech1').hide();
            }

        });



    });
</script>
