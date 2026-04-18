<form method="POST" action="{{ route('vehicle.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
    @csrf

    <div class="modal-body ms-5">


        <div class="row">


            {{-- <div class="form-group col-md-6">
                <label for="customer_id">Customer</label>

				<input class="form-control" placeholder="Search customer" type="search" id="search-query2">
				
                <select id="customer_id" name="client" class="customer_id" required>
                    @foreach ($clients as $id => $name)
                    <option value="{{ $id }}" {{ session('customer_id') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                    @endforeach
                </select>
            </div> --}}

            <div class="form-group col-md-6 position-relative">
                <label for="search-query2">Customer</label>

                <!-- Search input -->
                <input class="form-control mb-0" placeholder="Search customer" type="search" id="search-query2"
                    autocomplete="off">

                <!-- Hidden value field for selected client ID -->
                <input type="hidden" name="client" id="customer_id">

                <!-- Dropdown for live results -->
                <ul class="dropdown-menu w-100 show shadow-sm" id="search-results2"
                    style="display: none; max-height: 200px; overflow-y: auto;"></ul>
            </div>



            <!-- <div class="form-group col-md-6">

                {{ Form::label('state', 'Country', ['class' => 'form-label']) }}
                {!! Form::text('state', null, [
                    'class' => 'form-control hidesearch',
                    'required' => 'required',
                    'id' => 'country',
                ]) !!}
            </div> -->

            <div class="form-group col-md-6">
                <label for="rego">Reg Number</label>
                <input id="rego" name="rego" class="form-control" placeholder="Enter Reg No">
                <span id="error" class="text-danger"></span> <!-- Error message span -->
            </div>


            <div class="form-group col-md-6">
                <label for="state">State/Emirates</label>
                <select id="state" name="state">
                    <option value="">{{ __('Select State/Emirates') }}</option>
                    @foreach ($emirates as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="v_make">Vehicle Make</label>
                <select id="v_make" name="v_make" required>
                    <option value="">{{ __('Select Make') }}</option>
                    @foreach ($vm as $item)
                        <option value="{{ $item->id }}">{{ $item->make_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                {{ Form::label('vm', __('Vehicle Model'), ['class' => 'form-label']) }}
                <select class="form-control" name="vm" id="vm" required>

                </select>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('model_series', 'Year Series', ['class' => 'form-label']) }}
                <select name="model_series" class="">
                    <option value="0000">N/A
                    <option>
                        @for ($year = 1900; $year <= 2099; $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="enginespecs">Engine Specs</label>
                <select id="enginespecs" name="engine_specs" class="form-control">
                    <option value="">{{ __('Select Engine Spec') }}</option>
                </select>
            </div>


            <div class="form-group col-md-6">
                <label for="regional_specs">Regional Specs</label>
                <select id="regional_specs" name="regional_specs" class="regional_specs">

                    @foreach ($rs as $item)
                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                    @endforeach
                </select>


            </div>






            <div class="form-group col-md-6">
                {{ Form::label('vin', 'VIN', ['class' => 'form-label']) }}
                {{ Form::text('vin', null, ['class' => 'form-control', 'placeholder' => 'Enter VIN']) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('odometer', 'Odometer', ['class' => 'form-label']) }}
                {{ Form::number('odometer', null, ['class' => 'form-control', 'placeholder' => 'Enter Odometer Reading']) }}
            </div>


            <div class="form-group col-md-6">
                <label for="uom">UOM</label>
                <select id="uom" name="unit">
                    <option value="">{{ __('Select Unit') }}</option>

                    @foreach (['KM', 'MILES'] as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach

                    {{-- @foreach ($uom as $item)
                    @if (in_array($item->title, ['KM', 'MILES']))
                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                    @endif
                    @endforeach --}}
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary " id="close" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

    </div>
</form>

<script>
    $('#close').click(function() {
        $("#customModal").modal("hide");
    });

    $(document).ready(function() {


        $("select:not(#vm):not(#enginespecs)").selectize();
        $('.customer_id').change(function() {




            let id = $(this).val();

            $.ajax({

                url: '{{ route('users.country') }}',
                type: "get",
                dataType: 'json',

                data: {
                    id: id
                },

                success: function(res) {
                    $('#country').val(res.data);


                },
            });

        });

    });
    $(document).ready(function() {
        $('#v_make').on('change', function() {
            var makeId = $(this).val();
            var modelsDropdown = $('#vm');
            modelsDropdown.empty();
            modelsDropdown.append('<option value="">{{ __('Loading...') }}</option>');

            if (makeId) {
                $.ajax({
                    url: '/get-vehicle-models/' + makeId,
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        modelsDropdown.empty();
                        modelsDropdown.append(
                            '<option value="">{{ __('Select Model') }}</option>');

                        if (response.length > 0) {
                            $.each(response, function(index, model) {
                                modelsDropdown.append('<option value="' + model.id +
                                    '">' + model.model_name + '</option>');
                            });
                        } else {
                            modelsDropdown.append(
                                '<option value="">{{ __('No models available') }}</option>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                        modelsDropdown.empty();
                        modelsDropdown.append(
                            '<option value="">{{ __('An error occurred while fetching models.') }}</option>'
                        );
                        alert('{{ __('An error occurred while fetching models.') }}');
                    }
                });
            } else {
                modelsDropdown.empty();
                modelsDropdown.append('<option value="">{{ __('Select Model') }}</option>');
            }
        });

        $('#vm').on('change', function() {
            var makeId = $('#v_make').val();
            var modelId = $(this).val();
            var engineDropdown = $('#enginespecs');

            engineDropdown.empty();
            engineDropdown.append('<option value="">{{ __('Loading...') }}</option>');

            if (makeId && modelId) {
                $.ajax({
                    url: '/get-engine-specification?make_id=' + makeId + '&model_id=' + modelId,
                    type: 'GET',
                    success: function(response) {

                        console.log(response);
                        engineDropdown.empty();
                        engineDropdown.append(
                            '<option value="">{{ __('Select Engine Spec') }}</option>');

                        // Handle both array and single object response cases
                        if (Array.isArray(response) && response.length > 0) {
                            $.each(response, function(index, spec) {
                                engineDropdown.append('<option value="' + spec.id +
                                    '">' + spec.enginespecs + '</option>');
                            });
                        } else if (response && response.enginespecs) {
                            engineDropdown.append('<option value="' + response.enginespecs +
                                '">' + response.enginespecs + '</option>');
                        } else {
                            engineDropdown.append(
                                '<option value="">{{ __('No engine specs available') }}</option>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                        engineDropdown.empty();
                        engineDropdown.append(
                            '<option value="">{{ __('An error occurred while fetching engine specs.') }}</option>'
                        );
                        alert(
                            '{{ __('An error occurred while fetching engine specs.') }}'
                        );
                    }
                });
            } else {
                engineDropdown.empty();
                engineDropdown.append('<option value="">{{ __('Select Engine Spec') }}</option>');
            }
        });
    });


    // $('.close').click(function(){
    // $('.modal').hide();
    // })
</script>
<script>
    $(document).ready(function() {
        $('#rego').keyup(function() {
            let rego = $(this).val();
            $("#error").empty();

            if (rego.length > 0) {
                $.ajax({
                    url: '{{ route('check.rego') }}',
                    type: "GET",
                    dataType: 'json',
                    data: {
                        rego: rego
                    }, // Correcting the field name
                    success: function(res) {
                        console.log(res);
                        if (res.exists) { // Checking if rego exists
                            $("#error").text("This Rego Number already exists.");
                            $('#rego').addClass(
                                'is-invalid'); // Add Bootstrap invalid class
                        } else {
                            $("#error").text("");
                            $('#rego').removeClass(
                                'is-invalid'); // Remove invalid class if available
                        }
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr);
                        $("#error").text(
                            "An error occurred while checking the Rego Number.");
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        let typingTimer;
        const typingDelay = 1000; // 1 second delay

        $('#search-query2').on('keyup', function() {
            clearTimeout(typingTimer);
            let query = $(this).val();

            if (query.length < 2) {
                $('#search-results2').hide();
                return;
            }

            typingTimer = setTimeout(function() {
                $.ajax({
                    url: '{{ route('search2') }}', // your route here
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        const results = response.data;

                        if (results.length === 0) {
                            $('#search-results2').html(
                                '<li class="dropdown-item text-muted">No client found</li>'
                                ).show();
                            return;
                        }

                        let html = '';
                        results.forEach(function(item) {
                            html +=
                                `<li class="dropdown-item" data-id="${item.id}" data-name="${item.label}">${item.label}</li>`;
                        });

                        $('#search-results2').html(html).show();
                    },
                    error: function() {
                        $('#search-results2').html(
                            '<li class="dropdown-item text-danger">Error loading</li>'
                            ).show();
                    }
                });
            }, typingDelay);
        });

        // Handle result click
        $(document).on('click', '#search-results2 li', function() {
            const name = $(this).data('name');
            const id = $(this).data('id');

            $('#search-query2').val(name);
            $('#customer_id').val(id);
            $('#search-results2').hide();
        });

        // Hide dropdown if clicked outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#search-query2, #search-results2').length) {
                $('#search-results2').hide();
            }
        });

        // Show again on focus if data exists
        $('#search-query2').on('focus', function() {
            if ($('#search-results2').children().length > 0) {
                $('#search-results2').show();
            }
        });
    });
</script>
