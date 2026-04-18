<script>
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
						$('#v_model').append('<option value="' + model.id +
							'">' + model.model_name + '</option>');
					});
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

	$("#vehiclesDetailsEdit").click(function() {
		let rego = $('#rego').val();
		let v_make = $('#v_make').val();
		let vehicle_id = $('#selectedVehicleId').val();
		let v_model = $('#v_model').val();
		let model_series = $('#model_series').val();
		let vin = $('#vin').val();
		let Odometer = $('#Odometer').val();

		if (vehicle_id) {
			$.ajax({
				url: "{{ route('vehiclesDetailsEdit') }}",
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					rego,
					v_make,
					vehicle_id,
					v_model,
					model_series,
					vin,
					Odometer
				},
				dataType: "json",
				success: function(data) {
					if (data.status === 'success') {
						alert(data.message);
					}
				},
				error: function(xhr, status, error) {
					console.error('Error fetching engine specification:', error);
					$('#engine_spec').val('Error fetching specification');
				}
			});
		}
	});
	
    $("#bookingDetailsEdit").click(function() {
        let bookingId = $('#bookingId').val();
        let cientId = $('#cientId').val();
        let requestdate = $('#requestdate').val();
        let requesttime = $('#requesttime').val();
        let first_name = $('#first_name').val();
        let last_name = $('#last_name').val();
		
        let m_cc = $('#m_cc').val();
        let phone_number = $('#phone_number').val();
        let city = $('#city').val();
        let service_location = $('#service_location').val();
        let landmark = $('#landmark').val();
        let description = $('#description').val();		

        if (bookingId) {
            $.ajax({
                url: "{{ route('bookingDetailsEdit') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    bookingId,
                    cientId,
                    requestdate,
                    requesttime,
                    first_name,
                    last_name,
                    m_cc,
                    phone_number,
                    city,
                    service_location,
                    landmark,
					description,
                },
                dataType: "json",
                success: function(data) {
                    if (data.status === 'success') {
                        alert(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching engine specification:', error);
                    $('#engine_spec').val('Error fetching specification');
                }
            });
        }
    });

    $('.mapmodal_close').on('click', function() {
        $('#mapmodal').modal('hide');
    });

    $('#mapmodal_confirm').on('click', function() {
        $('#mapmodal').modal('hide');
        document.getElementById('service_location').value = document.getElementById('service_address1').value;
        document.getElementById('city').value = document.getElementById('service_city1').value;
    });

    let map; // Declare map and marker at the top level
    let marker;

    $('#mapmodal').on('shown.bs.modal', function() {
        initAutocomplete_map();
        google.maps.event.trigger(map, 'resize');
        if (marker) {
            map.setCenter(marker.getPosition());
        }
    });

    $('#mapmodal_click').on('click', function() {
        document.getElementById('service_address1').value = document.getElementById(
            'service_location').value;
        document.getElementById('service_city1').value = document.getElementById('city').value;

        initAutocomplete_map();
    });

    $(document).on('click', '.mapmodal_close', function() {
        $('#mapmodal').modal('hide');
    });

    var lat2, lng2;

    function initAutocomplete_map() {
        var address = document.getElementById('service_location').value;

        if (address == '') {
            address = 'Dubai';
        }

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status === 'OK') {
                var lat2 = results[0].geometry.location.lat();
                var lng2 = results[0].geometry.location.lng();
            }
        });

        // Initialize map
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: lat2,
                lng: lng2
            },
            zoom: 12
        });

        // Initialize marker
        var marker = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            draggable: true
        });

        // Geocode the default address to set map center and marker
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status === 'OK') {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                // Optionally, fill in the form fields with address components
                fillAddressComponents(results[0].address_components);
            }
        });

        // Autocomplete input field
        var input = document.getElementById('service_address1');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            document.getElementById('service_city1').value = '';
            document.getElementById('service_state1').value = '';
            document.getElementById('service_country1').value = '';
            document.getElementById('service_zip_code1').value = '';
            document.getElementById('service_address1').value = place.formatted_address;

            // Set address components
            place.address_components.forEach(function(component) {
                var types = component.types;
                if (types.includes('locality')) {
                    document.getElementById('service_city1').value = component.long_name;
                } else if (types.includes('administrative_area_level_1')) {
                    document.getElementById('service_state1').value = component.short_name;
                } else if (types.includes('country')) {
                    document.getElementById('service_country1').value = component.long_name;
                } else if (types.includes('postal_code')) {
                    document.getElementById('service_zip_code1').value = component
                        .long_name;
                }
            });

            // Update marker and map center based on the new location
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            marker.setPosition({
                lat: lat,
                lng: lng
            });
            map.setCenter({
                lat: lat,
                lng: lng
            });
        });

        // Listener for marker drag event to update address components
        google.maps.event.addListener(marker, 'dragend', function() {
            var lat = marker.getPosition().lat();
            var lng = marker.getPosition().lng();
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Reverse geocode to get address components
            geocoder.geocode({
                'location': {
                    lat: lat,
                    lng: lng
                }
            }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    document.getElementById('service_address1').value = results[0]
                        .formatted_address;
                    fillAddressComponents(results[0].address_components);
                }
            });
        });

        // Prevent form submission on Enter key press
        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }

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
</script>

<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO1Dw9T3wDRjN2RyrGLE2XTG86x46cIUc&loading=async&libraries=places">
</script>
