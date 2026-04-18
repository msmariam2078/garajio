@include('map.mapJS')

{{ Form::open(['url' => 'client', 'method' => 'post','id'=>'clientform']) }}

<style>
.pac-container {
    z-index: 10000 !important;
}

#map {
    height: 300px;
    width: 100%;
}

.modal {
    overflow-y: auto;
}

#mapmodal {
    z-index: 1050;
}

#customModal {
    z-index: 1060;
    /* Ensure it's higher than the first modal */
}
</style>

<div class="modal-body px-0 123">
    <div class="row d-flex justify-content-center">
        <div class="form-group d-flex justify-content-center">
            <div class="form-check client_type">
                {{ Form::radio('client_type', 'individual', true, ['class' => 'form-check-input', 'id' => 'type_individual']) }}
                {{ Form::label('type_individual', __('Individual'), ['class' => 'form-check-label individual active_customer_type', 'id' => 'individual']) }}
            </div>
            <div class="form-check client_type">
                {{ Form::radio('client_type', 'corporate', false, ['class' => 'form-check-input', 'id' => 'type_corporate']) }}
                {{ Form::label('type_corporate', __('Corporate'), ['class' => 'form-check-label corporate', 'id' => 'corporate']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <!--<div class="form-group col-md-12">-->
        <!--    {{ Form::label('trading_name', __('Trading Name'), ['class' => 'form-label']) }}-->
        <!--    {{ Form::text('trading_name', $company_name, ['class' => 'form-control', 'placeholder' => __(' '), 'required' => 'required']) }}-->
        <!--</div>-->
        <!-- <div class="form-group col-md-2">
            <select id="title" name="title" class="" placeholder="Select or type your Business name">
                <option value="Mr">Mr</option>
                <option value="Mrs">Mrs</option>
                <option value="Miss">Miss</option>
                <option value="Ms">Ms</option>
                <option value="Dr">Dr</option>
            </select>
        </div> -->

        <div class="form-group col-md-5">
            {{ Form::text('firstname', null, ['class' => 'form-control', 'placeholder' => __(' '), 'required' => 'required']) }}
            <label class="label">Primary Name</label>
        </div>
        <div class="form-group col-md-5">
            {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Last Name</label>
        </div>
        <div class="form-group col-md-6" id="div_corporate">
            <div class="form-group col-md-6" style="margin: 0px;">
            </div>
            <select id="business_name" name="business_name" class="">
                @foreach ($companies as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
		
		<div class="form-group col-md-6 d-none" id="gst-field">
			{{ Form::text('gst_number', null, ['class' => 'form-control', 'placeholder' => __(' ')]) }}
			<label class="label">GST / VAT Number</label>
		</div>

        <div class="form-group col-md-12">
            {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) }}

            <label class="label">Email</label>
            <span id="error" class="text-danger"></span>
        </div>

        <div class="form-group col-md-12">
            <!-- Country Code Select with UAE as Default -->

            <select id="c_tmpt" name="customer_template" class="" placeholder="Select" require>
                <label class="label">Customer Template</label>
                <option>Select Customer Template</option>
                @foreach ($customertemplate as $item) 
                <option value="{{ $item->id }}">{{ $item->code }}</option>
                @endforeach
            </select>
        </div>
        <!-- <div class="form-group col-md-12" id="company-name-indiv">
        <label for="company-select" class="label">Company</label>
        <select id="company-select" name="company" class="form-control" placeholder="Select or type your company">
            @foreach ($companies as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        </div> -->

        <!-- <div class="form-group col-md-12">
        {{ Form::label('time_zone', __('Time Zone'), ['class' => 'form-label']) }}
        {{ Form::text('time_zone', $timezone, ['class' => 'form-control', 'placeholder' => __('Time Zone'), 'readonly' => true]) }}
</div> -->

        <div class="form-group col-md-4">
            <!-- Country Code Select with UAE as Default -->

            <select id="m_cc" name="m_cc" class="" placeholder="Select">
                <option value='United Arab Emirates (+971)'>United Arab Emirates (+971)</option>
                @foreach ($country_code as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-8">
            <!-- Phone Number Input -->
            {{ Form::text('phone_number', null, ['class' => 'form-control numberonly', 'id' => 'phone_number']) }}
            <label class="label">Mobile</label>
            <span class="text-danger" id="error_number"></span>
        </div>
    </div>

    <div class="row d-none" id="additional-fields">
        <div class="form-group col-md-4">
            <select id="p_cc" name="p_cc" class="" placeholder="Select">
                <option value='United Arab Emirates (+971)'>United Arab Emirates (+971)</option>
                @foreach ($country_code as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>


        </div>
        <div class="form-group col-md-8">
            {{ Form::text('mobile', null, ['class' => 'form-control numberonly', 'id' => 'additional_phonenumber']) }}
            <label class="label">Whatsapp number</label>
        </div>
    </div>

    <button type="button" class="btn btn-primary w-100" style="margin-bottom: 25px;height:40px;"
        id="toggle-fields-btn">Add
        Mobile Number</button>

    <div class='row'>
        <div class="form-group col-md-4">
            <select id="country" name="country" class="" placeholder="Select">
                @foreach ($country as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4 d-none">
            {{ Form::text('fax', null, ['class' => 'form-control', 'placeholder' => __(' ')]) }}
            <label class="label">Fax</label>
        </div>
        <div class="form-group col-md-4">
            {{ Form::text('currency', $currency_s, ['class' => 'form-control', 'placeholder' => __('Currency ')]) }}
            <label class="label">Currency</label>
        </div>
        <div class="form-group col-md-4">
            {{ Form::text('po_box', null, ['class' => 'form-control numberonly', 'placeholder' => __(' ')]) }}
            <label class="label">P.O Box</label>
        </div>
        <div class="form-group col-md-4 d-none">
            {{ Form::label('note_contact', __('Notes to Contact'), ['class' => 'form-label']) }}
            {{ Form::text('note_contact', null, ['class' => 'form-control', 'placeholder' => __('Notes to Contact')]) }}
        </div>
        <div class="form-group col-md-4 d-none">
            {{ Form::label('note_customer', __('Notes to Customer'), ['class' => 'form-label']) }}
            {{ Form::text('note_customer', null, ['class' => 'form-control', 'placeholder' => __('Notes to Customer')]) }}
        </div>

    </div>
</div>

<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3 px-0">
    <label for="service_location" class="form-label">Service Address</label>
    {{-- <input type="text" id="service_location" name="service_location" class="form-control mt-2"
        placeholder="Service Address" readonly style="background-color: #e9ecef;"> --}}

    <a class="btn btn-primary" href="#" data-toggle="modal" id="mapmodal_click" data-target="#mapmodal">
        {{ __('Select Location on Map') }}
    </a>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::text('service_address', null, ['class' => 'form-control service_address', 'id' => 'service_address', 'placeholder' => __(' '), 'readonly' => 'true']) }}
        <label class="label">Service Address</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('service_city', null, ['class' => 'form-control service_city', 'id' => 'service_city', 'placeholder' => __(' '), 'readonly' => 'true']) }}
        <label class="label">Service City</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('service_state', null, ['class' => 'form-control service_state', 'id' => 'service_state', 'placeholder' => __(' '), 'readonly' => 'true']) }}
        <label class="label">Service State</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('service_country', null, ['class' => 'form-control service_country', 'id' => 'service_country', 'placeholder' => __(' '), 'readonly' => 'true']) }}
        <label class="label">Service Country</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('service_zip_code', null, ['class' => 'form-control service_zip_code', 'id' => 'service_zip_code', 'placeholder' => __(' '), 'readonly' => 'true']) }}
        <label class="label">Service Zip/Postal code</label>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::text('landmark', null, ['class' => 'form-control', 'placeholder' => __('Enter Landmark')]) }}
    </div>
</div>
<button type="button" id="toggle-address-form" class="btn btn-primary" style="margin-bottom: 15px;">Add
    Address</button>

<div id="address-form-template" class="row d-none">
    <div class="form-group col-md-12">
        {{-- <label for="address" class="label">Address</label> --}}
        <input type="text" id="address" name="addresses[][address]" class="form-control add_address"
            placeholder="Enter your address">
    </div>
    <div class="form-group col-md-6">
        {{-- <label for="city" class="label">City</label> --}}
        <input type="text" id="city" name="addresses[][city]" class="form-control add_city"
            placeholder="Enter your city">
    </div>
    <div class="form-group col-md-6">
        {{-- <label for="state" class="label">State</label> --}}
        <input type="text" id="state" name="addresses[][state]" class="form-control add_state"
            placeholder="Enter your state">
    </div>
    <div class="form-group col-md-6">
        {{-- <label for="country" class="label">Country</label> --}}
        <input type="text" id="country" name="addresses[][country]" class="form-control add_country"
            placeholder="Enter your country">
    </div>
    <div class="form-group col-md-6">
        {{-- <label for="zip_code" class="label">Zip/Postal Code</label> --}}
        <input type="text" id="zip_code" name="addresses[][zip_code]" class="form-control add_zip_code"
            placeholder="Enter your zip/postal code">
    </div>
</div>
<div id="address-container"></div>
<div class=" col-md-12 mb-20">
    <div class="form-group">
        <div class="form-check custom-chek">
            <input class="form-check-input" type="checkbox" value="billing_info" id="billing_info" name="billing_info">
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
        {{ Form::text('billing_city', null, ['class' => 'form-control', 'id' => 'billing_city', 'placeholder' => __(' ')]) }}
        <label class="label">Billing city</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('billing_state', null, ['class' => 'form-control', 'id' => 'billing_state', 'placeholder' => __(' ')]) }}
        <label class="label">Billing state</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('billing_country', null, ['class' => 'form-control', 'id' => 'billing_country', 'placeholder' => __(' ')]) }}
        <label class="label">Billing country</label>
    </div>
    <div class="form-group col-md-6">
        {{ Form::text('billing_zip_code', null, ['class' => 'form-control', 'id' => 'billing_zip_code', 'placeholder' => __(' ')]) }}
        <label class="label">Billing Zip/Postal code</label>
    </div>
    <div class="form-group col-md-12">
        {{ Form::textarea('billing_address', null, ['class' => 'form-control', 'id' => 'billing_address', 'rows' => 2, 'placeholder' => __(' ')]) }}
        <label class="label">Billing address</label>
    </div>
</div>
</div>
<!-- <div class="row" id="Virtual">
        <div class="form-group col-md-12">
            {{ Form::label('virtual', __('Virtual'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
            {{ Form::text('virtual', null, ['class' => 'form-control', 'placeholder' => __('Link')]) }}
        </div>
    </div> -->
</div>
<div class="modal-footer">
    {{ Form::submit(__('Create'), ['class' => 'btn btn-primary ml-10']) }}
</div>
<!-- Modal -->

<div class="modal fade bg-transparent" id="mapmodal" tabindex="-1" aria-labelledby="mapmodalLabel" aria-hidden="true">
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
                        {{ Form::label('service_address1', __('Address *'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_address1', null, ['class' => 'form-control service_address', 'id' => 'service_address1', 'placeholder' => __('service address')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_city1', __('City *'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_city1', null, ['class' => 'form-control service_city', 'placeholder' => __('service city')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_state1', __('State *'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_state1', null, ['class' => 'form-control service_state', 'placeholder' => __('service state')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_country1', __('Country *'), ['class' => 'form-label']) }} <span
                            class="text-danger"></span>
                        {{ Form::text('service_country1', null, ['class' => 'form-control service_country', 'placeholder' => __('service country')]) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('service_zip_code1', __('Zip Code *'), ['class' => 'form-label']) }} <span
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
                <button type="button" class="btn btn-secondary mapmodal_close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="mapmodal_confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>

{{ Form::close() }}
<script>
$("select").selectize(

);
</script>
<script>
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
    } else {
        document.getElementById('billing_city').value = "";
        document.getElementById('billing_state').value = "";
        document.getElementById('billing_country').value = "";
        document.getElementById('billing_zip_code').value = "";
        document.getElementById('billing_address').value = "";
    }
});
$(document).ready(function() {

    $('#email').keyup(function() {
        email = $(this).val();
        $("#error").empty();
        if (email.length > 0) {
            $.ajax({
                url: '{{ route('validate') }}',
                type: "get",
                dataType: 'json',
                data: {
                    email: email
                },
                success: function(res) {
                    console.log(res);
                    if (res.status == "error") {
                        $("#error").append(res.data.email[0]);
                        $("#clientform").submit(function(e) {
                            e.preventDefault();
                        });

                    } else {
                        $('#clientform').unbind('submit');
                    }
                }
            });
        }
    });




    $('#phone_number').keyup(function() {
        number = $(this).val();
        $("#error_number").empty();

        if (number.length > 0) {
            console.log(number);
            $.ajax({
                url: '{{ route('validatenumber') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "post",
                dataType: 'json',
                data: {
                    phone_number: number
                },
                success: function(res) {
                    if (res.status == "error") {
                        $("#error_number").append(res.data.phone_number[0]);
                        $('#phone_number').css("border-color", "red");
                        $("#clientform").submit(function(e) {
                            e.preventDefault();
                        });
                        console.log(res);
                    } else {
                        $('#phone_number').css("border-color", "green");
                        $('#clientform').unbind('submit');
                    }

                }
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    const $gstField = $('#gst-field');
    const $individual = $('#individual');
    const $corporate = $('#corporate');
    const $div_corporate = $('#div_corporate');

    function toggleGstField() {
        if ($('#type_corporate').is(':checked')) {
            $gstField.removeClass('d-none');
            $div_corporate.removeClass('d-none');
            $individual.removeClass('active_customer_type');
            $corporate.addClass('active_customer_type');
        } else {
            $gstField.addClass('d-none');
            $div_corporate.addClass('d-none');
            $individual.addClass('active_customer_type');
            $corporate.removeClass('active_customer_type');
        }
    }
    toggleGstField();
    $('input[name="client_type"]').change(function() {
        toggleGstField();
    });
});
</script>
<script>
$(document).ready(function() {

    const $toggleButton = $('#toggle-address-form');
    const $addressContainer = $('#address-container');
    const $addressFormTemplate = $('#address-form-template');

    $toggleButton.on('click', function() {
        const $formClone = $addressFormTemplate.clone();
        if ($addressContainer.children().length === 0) {

            $formClone.removeClass('d-none');
            $formClone.removeAttr('id');
            $addressContainer.append($formClone);
        } else {

            $formClone.removeClass('d-none');
            $formClone.removeAttr('id');
            $addressContainer.append($formClone);
        }
    });
});
</script>
<script>
$(document).on('click', '.mapmodal_close', function() {
    $('#mapmodal').modal('hide');
    $('#customModal').modal('show');
});

$('#mapmodal_confirm').on('click', function() {
    $('#mapmodal').modal('hide');

    document.getElementById('service_city').value = document.getElementById('service_city1').value;
    document.getElementById('service_state').value = document.getElementById('service_state1').value;
    document.getElementById('service_country').value = document.getElementById('service_country1')
        .value;
    document.getElementById('service_zip_code').value = document.getElementById('service_zip_code1')
        .value;
    document.getElementById('service_address').value = document.getElementById('service_address1')
        .value;
});

$('.numberonly').keypress(function(e) {

    var charCode = (e.which) ? e.which : event.keyCode

    if (String.fromCharCode(charCode).match(/[^0-9]/g))

        return false;
});

let map;
let marker;

$(document).on('shown.bs.modal', '#mapmodal', function() {
    initAutocomplete_map();
    google.maps.event.trigger(map, 'resize');
    if (marker) {
        map.setCenter(marker.getPosition());
    }
});
$('#mapmodal').on('shown.bs.modal', function() {
    initAutocomplete_map();
    google.maps.event.trigger(map, 'resize');
    if (marker) {
        map.setCenter(marker.getPosition());
    }
});
$('#mapmodal_click').on('click', function() {
    initAutocomplete_map();
});
$('#map_click').on('click', function() {
    initMap();
});

function initMap() {
    const myLatLng = {
        lat: -25.363,
        lng: 131.044
    };
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 4,
        center: myLatLng,
    });
    new google.maps.Marker({
        position: myLatLng,
        map,
        title: "Hello World!",
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('m_crc');
    const choices = new Choices(element, {
        removeItemButton: true,
        maxItemCount: -1,
        searchResultLimit: 5,
        renderSelectedChoices: 'always'
    });
});
</script>

<script>
const validPhoneStyle = '2px solid green';
const invalidPhoneStyle = '2px solid red';


// Function to handle input validation
// Function to handle input validation
function validatePhoneInput(inputElement, code) {
    inputElement.addEventListener('input', function() {
        let phoneNumber = this.value;

        // Restrict user input to only 9 digits
        const digits = phoneNumber.slice(0, 10);

        // Set the input value to the formatted phone number
        this.value = digits;
        let PhoneRegex = /^[1-9]{9}$/;
        if (code.value === 'India (+91)')
        //Validate the phone number format
        {

            PhoneRegex = /^[1-9]{10}$/;
        }
        if (code.value === 'Qatar (+974)' || code.value === 'Oman (+968)' || code.value === 'Kuwait (+965)')
        //Validate the phone number format
        {

            PhoneRegex = /^[1-9]{8}$/;
        }
        if (code.value === 'Bahrain (+973)' || code.value === 'Yemen (+967)')
        //Validate the phone number format
        {

            PhoneRegex = /^[1-9]{7}$/;
        }
        // Apply the border color based on the validity of the phone number
        if (PhoneRegex.test(digits)) {
            this.style.border = validPhoneStyle; // Set border to green for valid
        } else {
            this.style.border = invalidPhoneStyle; // Set border to red for invalid
        }
    });
}
</script>

<script>
$(document).ready(function() {
    const toggleBtn = document.getElementById('toggle-fields-btn');
    const additionalFields = document.getElementById('additional-fields');

    toggleBtn.addEventListener('click', function() {
        if (additionalFields.classList.contains('d-none')) {
            additionalFields.classList.remove('d-none');
            toggleBtn.textContent = 'Remove Mobile Number';
        } else {
            additionalFields.classList.add('d-none');
            toggleBtn.textContent = 'Add Mobile Number';
        }
    });
});
</script>