@include('map.mapJS2')

{{ Form::model($technician, ['route' => ['technician.update', $technician->id], 'method' => 'PUT', 'files' => true]) }}
<div class="modal-body px-0">
    <div class="row">
        <div class="form-group col-md-12" style="margin: 0px;">
            {{ Form::label('firstname', __('User Information'), ['class' => 'form-label']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => __(' '), 'required' => 'required']) }}
            <label class="label">First Name</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Last Name</label>
        </div>
               <div class="form-group col-md-12">
            <select name="type"  class="form-control" required>
                <option value=''>Select A Type</option>
            
                <option value="technician" {{$technician->type=='technician' ? 'selected' : ''}}>Technician</option>
                <option value="supervisor" {{$technician->type=='supervisor' ? 'selected' : ''}}>Supervisor</option>
    
            </select>
        </div>
        <div class="form-group col-md-12">

            <input type="file" name='profile_picture' class="dropify" data-height="100" accept='image/*' />

            <label class="label">Profile Picture</label>
        </div>
        <!-- @php
        $warehouseOptions = $warehouse->pluck('name', 'id')->toArray();
        $selectedWarehouses = $technician->warehouses->pluck('id')->toArray();
        @endphp
        <div class="form-group col-md-12" style="margin: 0px;">
            {{ Form::label('warehouse_name', __('Warehouse Name'), ['class' => 'form-label']) }}
        </div>
        <div class="form-group col-md-12">
            <select name="warehouse_name[]" id='m_cc' class="selectize" multiple>
                <option value=""></option>
                @foreach($warehouseOptions as $id => $name)
                @foreach($selectedWarehouses as $sid )
                <option value="{{ $id }}" {{($id==$sid)? 'selected' : ''}}>{{ $name }}</option>
                @endforeach
                @endforeach
            </select>


        </div> -->
        <div class="form-group col-md-12">
            {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __(' '), 'required' => 'required']) }}
            <label class="label">Email</label>
        </div>
        <div class="form-group col-md-4">
            <select name="m_cc" id='m_cc'>
                <option value=""></option>
                @foreach($country_code as $id => $name)

                <option value="{{ $id }}" {{($id==$technician->ccm)? 'selected' : ''}}>{{ $name }}</option>

                @endforeach
            </select>

        </div>
        <div class="form-group col-md-8">
            {{ Form::text('phone_number', $technician->phone_number, ['class' => 'form-control numberonly', 'placeholder' => __(' '), 'required' => 'required']) }}
            <label class="label">Mobile</label>
        </div>
        <div class="form-group col-md-4">
            <select name="p_cc" id='p_cc'>
                <option value=""></option>
                
                @foreach($country_code as $id => $name)

                <option value="{{ $id }}" {{($id==$technician->ccp)? 'selected' : ''}}>{{ $name }}</option>

                @endforeach
            </select>

        </div>
        <div class="form-group col-md-8">
            {{ Form::text('mobile', $technician->mobile, ['class' => 'form-control numberonly', 'placeholder' => __(' ')]) }}
            <label class="label">Phone</label>
        </div>
       
        </div>
    
    
      
      
     
    
    <div class="form-group col-md-12">
        <label for="skill_group" class="form-label">{{ __('Skill Group') }}</label>
        <select id="skill_group" name="skill_group[]" class="selectize" multiple>
            <option value="">Select Skill Group</option>
            @php
            $selectedSkills = json_decode($technician->skills, true) ?? []; // Decode JSON if stored as JSON
            @endphp
            @foreach ($skills as $value)
            <option value="{{ $value->id }}" {{ in_array($value->id, (array) $selectedSkills) ? 'selected' : '' }}>
                {{ $value->group_name }}
            </option>
            @endforeach
        </select>
    </div>

    @php
		$technicianShift = json_decode($technician->shift, true) ?? [];
    @endphp

    <div class="form-group col-md-12">
        {{ Form::label('shift', __('Shift'), ['class' => 'form-label']) }}
        <select class="selectize" name="shift[]" id="shift" multiple>
            <option value="" disabled>Select</option>
            @foreach ($shifts as $name)
            <option value="{{ $name->id }}" {{ in_array($name->id, (array) $technicianShift) ? 'selected' : '' }}>
                {{ $name->title }}
            </option>
            @endforeach
        </select>
    </div>


    <div class="form-group col-md-12">
        {{ Form::text('fax', null, ['class' => 'form-control', 'placeholder' => __(' ')]) }}
        <label class="label">Fax</label>
    </div>
    <div class="form-group col-md-12">
        {{ Form::text('po_box', null, ['class' => 'form-control', 'placeholder' => __(' ')]) }}
        <label class="label">P.O Box</label>
    </div>
    <div class="form-group col-md-12">
        <select name="country" id='country'>
            <option value="">select country</option>
            @foreach($country as $id => $name)

            <option value="{{ $id }}" {{ $id == $technician->country  ? 'selected' : '' }}>{{ $name }}</option>

            @endforeach
        </select>

    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-20">
        <h5> {{ __('Service Address') }}</h5>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#mapmodal"
            id="mapmodal_click">
            Select location on map
        </button>
    </div>
    <div class="row px-3">
        <div class="form-group col-md-12">
            {{ Form::text('service_address',  !empty($technician->clients) ? $technician->clients->service_address : '', ['class' => 'form-control service_address','id'=>'service_address',  'placeholder' => __(' '), 'readonly' => 'true']) }}
            <label class="label">Service Address</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('service_city', !empty($technician->clients) ? $technician->clients->service_city : '', ['class' => 'form-control service_city','id'=>'service_city', 'placeholder' => __(' '), 'readonly' => 'true']) }}
            <label class="label">Service City</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('service_state', !empty($technician->clients) ? $technician->clients->service_state : '', ['class' => 'form-control service_state','id'=>'service_state', 'placeholder' => __(' '), 'readonly' => 'true']) }}
            <label class="label">Service State</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('service_country', !empty($technician->clients) ? $technician->clients->service_country : '', ['class' => 'form-control service_country','id'=>'service_country', 'placeholder' => __(' '), 'readonly' => 'true']) }}
            <label class="label">Service Country</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('service_zip_code', !empty($technician->clients) ? $technician->clients->service_zip_code : '', ['class' => 'form-control service_zip_code','id'=>'service_zip_code', 'placeholder' => __(' '), 'readonly' => 'true']) }}
            <label class="label">Service Zip/Postal code</label>
        </div>

    </div>
</div>
</div>
<div class="modal-footer">
    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary ml-10']) }}
</div>

<!-- Modal -->
<div class="modal fade" id="mapmodal" tabindex="-1" aria-labelledby="mapmodalLabel" aria-hidden="true"
    style="background-color: #000000a8;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapmodalLabel">Please confirm location by dragging the marker on the map
                </h5>
                <button type="button" class="close mapmodal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        {{ Form::label('service_address1', __('Address*'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_address1', null, ['class' => 'form-control service_address','id'=>'service_address1',  'placeholder' => __('service address')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_city1', __('City*'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_city1', null, ['class' => 'form-control service_city', 'placeholder' => __('service city')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_state1', __('State*'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_state1', null, ['class' => 'form-control service_state', 'placeholder' => __('service state')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_country1', __('Country*'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_country1', null, ['class' => 'form-control service_country', 'placeholder' => __('service country')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_zip_code1', __('Zip Code*'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_zip_code1', null, ['class' => 'form-control service_zip_code', 'placeholder' => __('service zip code')]) }}
                    </div>
                    <div class="form-group col-md-12">
                        <div id="map"></div>
                    </div>
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mapmodal_close">Cancel</button>
                <button type="button" class="btn btn-primary" id="mapmodal_confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
<script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>
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
  $('select').selectize(); 
$('.mapmodal_close').on('click', function() {
    $('#mapmodal').modal('hide');
});

$('#mapmodal_confirm').on('click', function() {
    $('#mapmodal').modal('hide');

    document.getElementById('service_city').value = document.getElementById('service_city1').value;
    document.getElementById('service_state').value = document.getElementById('service_state1').value;
    document.getElementById('service_country').value = document.getElementById('service_country1').value;
    document.getElementById('service_zip_code').value = document.getElementById('service_zip_code1').value;
    document.getElementById('service_address').value = document.getElementById('service_address1').value;

});





$('.numberonly').keypress(function(e) {

    var charCode = (e.which) ? e.which : event.keyCode

    if (String.fromCharCode(charCode).match(/[^0-9]/g))

        return false;

});
</script>