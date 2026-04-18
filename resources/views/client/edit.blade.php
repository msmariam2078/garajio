@include('map.mapJS2')

{{ Form::model($user, ['route' => ['client.update', $user->id], 'method' => 'PUT']) }}
<div class="modal-body p-0">
    <div class="row justify-content-center pt-2">
        <div class="form-group  d-flex justify-content-center ">
            {{ Form::hidden('hide_client_type', !empty($user->clients) ? $user->clients->type : '', ['class' => 'form-control']) }}
            <div class="form-check client_type">
                {{ Form::radio('client_type', 'individual', $user->clients->type = 'Individual' ? true : false, ['class' => 'form-check-input', 'id' => 'type_individual']) }}
                {{ Form::label('type_individual', __('Individual'), ['class' => 'form-check-label individual active_customer_type']) }}
            </div>
            <div class="form-check client_type">
                {{ Form::radio('client_type', 'corporate', $user->clients->type = 'Corporate' ? true : false, ['class' => 'form-check-input', 'id' => 'type_corporate']) }}
                {{ Form::label('type_corporate', __('Corporate'), ['class' => 'form-check-label corporate']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <!--<div class="form-group col-md-12">-->
        <!--    {{ Form::label('trading_name', __('Trading Name'), ['class' => 'form-label']) }}-->
        <!--    {{ Form::select('trading_name', $wareHouses, !empty($user->clients) ? $user->clients->trading_name : '', ['class' => 'form-control basic-select', 'id' => 'trading_name']) }}-->
        <!--</div>-->
        <div class="form-group col-md-12">
            {{ Form::label('firstname', __('Primary Contact'), ['class' => 'form-label']) }}
        </div>
        <!--<div class="form-group col-md-2">-->
        <!--{{ Form::select('title', $title, !empty($user) ? $user->title : '', ['class' => 'form-control basic-select', 'id' => 'title']) }}-->
        <!--</div>-->
        <div class="form-group col-md-6">
            {{ Form::text('firstname', !empty($user) ? $user->first_name : '', ['class' => 'form-control', 'placeholder' => __(' '), 'required' => 'required']) }}
            <label class="label">Primary Name</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('last_name', !empty($user) ? $user->last_name : '', ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Last Name</label>
        </div>
        <div class="form-group col-md-6" id="div_corporate">
            <select id="business_name" name="business_name" class=""
                placeholder="">
                @foreach ($companies as $value => $label)
                    <option value="{{ $value }}"
                        {{ !empty($user->clients) && $user->clients->business_name === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>       

		<div class="form-group col-md-6">
			{{ Form::text('gst_number', !empty($user) ? $user->gst : '', ['class' => 'form-control', 'placeholder' => __(' ')]) }}
			<label class="label">GST / VAT Number</label>
		</div>

        <div class="form-group col-md-6">
            {{ Form::email('email', !empty($user) ? $user->email : '', ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Email</label>
        </div>        

        <div class="form-group col-md-6 d-none" id="company-name-indiv">
            <label for="company-select" class="label">Company</label>
            <select id="company-select" name="company" class="" placeholder="">
                @foreach ($companies as $value => $label)
                    <option value="{{ $value }}"
                        {{ !empty($user->clients) && $user->clients->company === $value ? 'selected' : '' }}>
                        {{ $label }}</option>
                @endforeach
            </select>
        </div>

        <!--<div class="form-group col-md-12">-->
        <!--    {!! Form::text('credit_limit', !empty($user->clients) ? $user->clients->credit_limit : '0.00', [
            'class' => 'form-control',
            'placeholder' => ' ',
        ]) !!}-->
        <!--<label class="label">Credit Limit</label>-->
        <!--</div>-->
        <div class="form-group col-md-6">

            <select id="m_cc" name="m_cc" class="" placeholder="">
                @foreach ($country_code as $value => $label)
                    <option value="{{ $value }}" {{ $value == $user->ccm ? 'selected' : '' }}>
                        {{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('phone_number', !empty($user) ? $user->phone_number : '', ['class' => 'form-control', 'placeholder' => __(' '), 'required' => 'required']) }}
            <label class="label">Phone Number</label>
        </div>
    </div>

	<div class="form-group col-md-12">
		<!-- Country Code Select with UAE as Default -->

		<select id="c_tmpt" name="customer_template" class="" placeholder="Select">
			<label class="label">Customer Template</label>
			<option>Select Customer Template</option>
			@foreach ($customertemplate as $item) 
			<option value="{{ $item->id }}">{{ $item->code }}</option>
			@endforeach
		</select>
	</div>

    <div class="row d-none" id="additional-fields">
        <div class="form-group col-md-6">
            <select id="p_cc" name="p_cc" class="" placeholder="">
                @foreach ($country_code as $value => $label)
                    <option value="{{ $value }}" {{ $value == $user->ccm ? 'selected' : '' }}>
                        {{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('mobile', !empty($user) ? $user->mobile : '', ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Mobile</label>
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-block mb-3" id="toggle-fields-btn">Add Mobile Number</button>

    <div class="row">
        <div class="form-group col-md-6 d-none">
            {{ Form::text('fax', !empty($user) ? $user->fax : '', ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Fax</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::text('po_box', !empty($user) ? $user->po_box : '', ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">P.O Box</label>
        </div>
        <div class="form-group col-md-6 d-none">
            {{ Form::text('note_contact', !empty($user->clients) ? $user->clients->note_contact : '', ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Notes to Contact</label>
        </div>
        <div class="form-group col-md-6 d-none">
            {{ Form::text('note_customer', !empty($user->clients) ? $user->clients->note_customer : '', ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Notes to Customer</label>
        </div>
        <div class="form-group col-md-6">
            <select id="country" name="country" class="" placeholder="">
                @foreach ($country as $value => $label)
                    <option value="{{ $value }}" {{ $value == $user->country ? 'selected' : '' }}>
                        {{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-2">
        <label for="service_location" class="form-label">Service Address</label>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#mapmodal"
            id="mapmodal_click">
            Select location on map
        </button>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::text('service_address', !empty($user->clients) ? $user->clients->service_address : '', ['class' => 'form-control service_address', 'id' => 'service_address', 'placeholder' => __(' ')]) }}
        <label class="label">Address</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('service_city', !empty($user->clients) ? $user->clients->service_city : '', ['class' => 'form-control service_city', 'id' => 'service_city', 'placeholder' => __(' ')]) }}
        <label class="label">City</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('service_state', !empty($user->clients) ? $user->clients->service_state : '', ['class' => 'form-control service_state', 'id' => 'service_state', 'placeholder' => __(' ')]) }}
        <label class="label">State</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('service_country', !empty($user->clients) ? $user->clients->service_country : '', ['class' => 'form-control service_country', 'id' => 'service_country', 'placeholder' => __(' ')]) }}
        <label class="label">Country</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('service_zip_code', !empty($user->clients) ? $user->clients->service_zip_code : '', ['class' => 'form-control service_zip_code', 'id' => 'service_zip_code', 'placeholder' => __(' ')]) }}
        <label class="label">Zip Code</label>
    </div>

</div>
<button type="button" id="toggle-address-form" class="btn btn-primary px-3" style="margin-bottom: 15px;">Add
    Address</button>

<div id="address-form-template" class="row d-none px-3">
    <div class="form-group col-md-12">
        <input type="text" name="addresses[][address]" class="form-control add_address" placeholder="Address">

    </div>
    <div class="form-group col-md-6">
        <input type="text" name="addresses[][city]" class="form-control add_city" placeholder="City">
        
    </div>
    <div class="form-group col-md-6">
        <input type="text" name="addresses[][state]" class="form-control add_state" placeholder="State">
       
    </div>
    <div class="form-group col-md-6">
        <input type="text" name="addresses[][country]" class="form-control add_country" placeholder="Country">
       
    </div>
    <div class="form-group col-md-6">
        <input type="text" name="addresses[][zip_code]" class="form-control add_zip_code"
            placeholder="Zip/Postal code">
      
    </div>
</div>

<div id="address-container"></div>

<div class=" col-md-12 mb-20">
    <div class="form-group">
        <div class="form-check custom-chek">
            <input class="form-check-input" type="checkbox" value="billing_info" id="billing_info"
                name="billing_info">
            <label class="form-check-label" for="billing_info">
                <h5> {{ __('Billing Address') }}</h5>
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="form-check custom-chek">
            <input class="form-check-input" type="checkbox" value="same_info" id="same_info" name="same_info">
            <label class="form-check-label" for="same_info">
                <h5> {{ __('Same with Service Address') }}</h5>
            </label>
        </div>
    </div>
</div>
<div class="row billing_info d-none">
    <div class="form-group col-md-6">
        {{ Form::text('billing_city', !empty($user->clients) ? $user->clients->billing_city : '', ['class' => 'form-control', 'id' => 'billing_city', 'placeholder' => __(' ')]) }}
        <label class="label">City</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('billing_state', !empty($user->clients) ? $user->clients->billing_state : '', ['class' => 'form-control', 'id' => 'billing_state', 'placeholder' => __(' ')]) }}
        <label class="label">State</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('billing_country', !empty($user->clients) ? $user->clients->billing_country : '', ['class' => 'form-control', 'id' => 'billing_country', 'placeholder' => __(' ')]) }}
        <label class="label">Country</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('billing_zip_code', !empty($user->clients) ? $user->clients->billing_zip_code : '', ['class' => 'form-control', 'id' => 'billing_zip_code', 'placeholder' => __(' ')]) }}
        <label class="label">Zip Code</label>
    </div>
    <div class="form-group col-md-12">
        {{ Form::textarea('billing_address', !empty($user->clients) ? $user->clients->billing_address : '', ['class' => 'form-control', 'id' => 'billing_address', 'rows' => 2, 'placeholder' => __(' ')]) }}
        <label class="label">Address</label>
    </div>
</div>
</div>
<div class="row" id="Virtual">
    <div class="form-group col-md-12">
        {{ Form::label('virtual', __('Virtual'), ['class' => 'form-label']) }}
        {{ Form::text('virtual', null, ['class' => 'form-control', 'placeholder' => __('Link')]) }}
    </div>
</div>

</div>
<div class="modal-footer">
    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary ml-10']) }}
</div>

<!-- Modal -->
<div class="modal fade" id="mapmodal" tabindex="-1" aria-labelledby="mapmodalLabel" aria-hidden="true">
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
                        {{ Form::label('service_address1', __('Address'), ['class' => 'form-label']) }}
                        {{ Form::text('service_address1', null, ['class' => 'form-control service_address', 'id' => 'service_address1', 'placeholder' => __('service address')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_city1', __('City'), ['class' => 'form-label']) }}
                        {{ Form::text('service_city1', null, ['class' => 'form-control service_city', 'id' => 'service_city1', 'placeholder' => __('service city')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_state1', __('State'), ['class' => 'form-label']) }}
                        {{ Form::text('service_state1', null, ['class' => 'form-control service_state', 'id' => 'service_state1', 'placeholder' => __('service state')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_country1', __('Country'), ['class' => 'form-label']) }}
                        {{ Form::text('service_country1', null, ['class' => 'form-control service_country', 'id' => 'service_country1', 'placeholder' => __('service country')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_zip_code1', __('Zip Code'), ['class' => 'form-label']) }}
                        {{ Form::text('service_zip_code1', null, ['class' => 'form-control service_zip_code', 'id' => 'service_zip_code1', 'placeholder' => __('service zip code')]) }}
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
<script>
    $("select").selectize();

    const toggleBtn2 = document.getElementById('toggle-fields-btn');
    const additionalFields2 = document.getElementById('additional-fields');
    toggleBtn2.addEventListener('click', function() {
        if (additionalFields2.classList.contains('d-none')) {
            additionalFields2.classList.remove('d-none');
            toggleBtn2.textContent = 'Remove Mobile Number';
        } else {
            additionalFields2.classList.add('d-none');
            toggleBtn2.textContent = 'Add Mobile Number';
        }
    });
</script>
<script>
     
    
    $(document).ready(function() {
      
        const $toggleButton = $('#toggle-address-form');
        const $addressContainer = $('#address-container');
        const $addressFormTemplate = $('#address-form-template');

        // Pre-populate the form if there are existing addresses
        const existingAddresses = @json($user->clients->addresses ? json_decode($user->clients->addresses, true) : []);

        // Helper function to group fields into addresses
        function groupAddresses(fields) {
            const groupedAddresses = [];
            for (let i = 0; i < fields.length; i += 5) {
                groupedAddresses.push({
                    address: fields[i].address || '',
                    city: fields[i + 1].city || '',
                    state: fields[i + 2].state || '',
                    country: fields[i + 3].country || '',
                    zip_code: fields[i + 4].zip_code || ''
                });
            }
            return groupedAddresses;
        }

        // Convert the flat structure into a grouped structure
        const groupedAddresses = groupAddresses(Object.values(existingAddresses));

        if (groupedAddresses.length > 0) {
            groupedAddresses.forEach(function(address, index) {
                if (address.address || address.city || address.state || address.country || address
                    .zip_code) {
                    const $formClone = $addressFormTemplate.clone();
                    $formClone.removeClass('d-none');
                    $formClone.removeAttr('id');
                    $formClone.find('.add_address').val(address.address);
                    $formClone.find('.add_city').val(address.city);
                    $formClone.find('.add_state').val(address.state);
                    $formClone.find('.add_country').val(address.country);
                    $formClone.find('.add_zip_code').val(address.zip_code);
                    $addressContainer.append($formClone);
                }
            });
        }

        $toggleButton.on('click', function() {
            // Clone the address form template
            const $formClone = $addressFormTemplate.clone();

            // Add a new address form
            $formClone.removeClass('d-none');
            $formClone.removeAttr('id');
            $addressContainer.append($formClone);
        });
    });
    $(document).ready(function() {
        const $gstField = $('#gst-field');

        function toggleGstField() {
            if ($('#type_corporate').is(':checked')) {
                $gstField.removeClass('d-none');
            } else {
                $gstField.addClass('d-none');
            }
        }
        toggleGstField();
        $('input[name="client_type"]').change(function() {
            toggleGstField();
        });
    });
</script>
<script>
    $('.mapmodal_close').on('click', function() {
        $('#mapmodal').modal('hide');
    });

    $('#billing_info').on('click', function() {
        if ($(this).is(":checked")) {
            $('.billing_info').removeClass('d-none');
        } else {
            $('.billing_info').addClass('d-none');
        }
    });

    $('#same_info').on('click', function() {
        if ($(this).is(":checked")) {
            document.getElementById('billing_city').value = document.getElementById('service_city').value;
            document.getElementById('billing_state').value = document.getElementById('service_state').value;
            document.getElementById('billing_country').value = document.getElementById('service_country').value;
            document.getElementById('billing_zip_code').value = document.getElementById('service_zip_code')
                .value;
            document.getElementById('billing_address').value = document.getElementById('service_address').value;
        }
    });

    function handleSectionVisibility() {
        if ($('input[type=radio][name=type]:checked').val() === 'fix') {
            $('#Fixed').show();
            $('#Virtual').hide();
            $('.mobile-map').addClass('d-none');
            $('.ware_house').removeClass('d-none');
            $('#Fixed input, #Fixed textarea').prop('required', true);
        } else if ($('input[type=radio][name=type]:checked').val() === 'mobile') {
            $('#Fixed').show();
            $('#Virtual').hide();
            $('.mobile-map').removeClass('d-none');
            $('.ware_house').addClass('d-none');
            $('#Fixed input, #Fixed textarea').prop('required', false);
        } else if ($('input[type=radio][name=type]:checked').val() === 'virtual') {
            $('#Fixed').hide();
            $('#Virtual').show();
            $('.mobile-map').addClass('d-none');
            $('.ware_house').addClass('d-none');
            $('#Fixed input, #Fixed textarea').prop('required', false);
        }
    }

    $(document).ready(function() {
        $("#div_corporate").hide();
        $('#Virtual').hide();
        handleSectionVisibility();
        $('input[type=radio][name=type]').change(function() {
            handleSectionVisibility();
        });

        $(".form-check-label").removeClass("active_customer_type");
        if ($('input[type=hidden][name=hide_client_type]').val() == 'individual') {
            $("#company-name-indiv").hide();
            $("#div_corporate").hide();
            $(".individual").addClass("active_customer_type");
        } else {
            $("#div_corporate").show();
            $(".corporate").addClass("active_customer_type");
        }

        $('#billing_info').prop("checked", true);
        $('.billing_info').removeClass('d-none');
    });

    $('input[type=radio][name=client_type]').change(function() {
        $(".form-check-label").removeClass("active_customer_type");
        if (this.value == 'individual') {
            $("#div_corporate").hide();
            $("#div_corporate").hide();
            $(".individual").addClass("active_customer_type");
        } else {
            $("#div_corporate").show();
            $("#div_corporate").hide();
            $(".corporate").addClass("active_customer_type");
        }
    });


    $('.numberonly').keypress(function(e) {

        var charCode = (e.which) ? e.which : event.keyCode

        if (String.fromCharCode(charCode).match(/[^0-9]/g))

            return false;
    });
</script>