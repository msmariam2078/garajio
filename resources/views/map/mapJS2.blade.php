<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap4.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

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

<script>
    $(document).on('click', '.mapmodal_close', function() {
        $('#mapmodal').modal('hide');
        $('#customModal').modal('show');
    });

    // $(document).on('click', '#mapmodal_confirm', function() {
    // });

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

    let map; // Declare map and marker at the top level
    let marker;

    $(document).on('shown.bs.modal', '#mapmodal', function() {
        initAutocomplete_map();
        google.maps.event.trigger(map, 'resize');
        if (marker) {
            map.setCenter(marker.getPosition());
        }
    });

    // $('#mapmodal').on('shown.bs.modal', function() {
    //     initAutocomplete_map2();
    //     google.maps.event.trigger(map, 'resize');
    //     if (marker) {
    //         map.setCenter(marker.getPosition());
    //     }
    // });
    $('#mapmodal_click').on('click', function() {
        const fields = ['service_address', 'service_city', 'service_state', 'service_country',
            'service_zip_code'
        ];
    
        fields.forEach(function(field) {
            const source = document.getElementById(field);
            const target = document.getElementById(field + '1');
    
            if (source && target && source.value.trim() !== '') {
                target.value = source.value;
            }
        });

        initAutocomplete_map();
    });

    var lat2, lng2;

    function initAutocomplete_map() {
        var address = document.getElementById('service_address').value;

        if(address == ''){
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
                    document.getElementById('service_zip_code1').value = component.long_name;
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
                    document.getElementById('service_address1').value = results[0].formatted_address;
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

    // Helper function to populate address components into form fields
    function fillAddressComponents(components) {
        components.forEach(function(component) {
            var types = component.types;
            if (types.includes('locality')) {
                document.getElementById('service_city1').value = component.long_name;
            } else if (types.includes('administrative_area_level_1')) {
                document.getElementById('service_state1').value = component.short_name;
            } else if (types.includes('country')) {
                document.getElementById('service_country1').value = component.long_name;
            } else if (types.includes('postal_code')) {
                document.getElementById('service_zip_code1').value = component.long_name;
            }
        });
    }
</script>

<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO1Dw9T3wDRjN2RyrGLE2XTG86x46cIUc&loading=async&libraries=places">
</script>

<script>
    $(document).ready(function() {
        $('#m_cc, #p_cc, #group, #country, #company-select, .form-group select').selectize();
    });
</script>
