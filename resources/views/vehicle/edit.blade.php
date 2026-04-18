<form method="POST" action="{{ route('vehicle.update', $vehicle->id) }}" accept-charset="UTF-8"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal-body ms-5" id="customModal">
        <div class="row">
            <div class="form-group col-md-6 position-relative">
                <label for="search-query2">Customer</label>

				<input class="form-control mb-0" placeholder="Search customer" type="search" id="search-query2" autocomplete="off"
					value="{{ old('client_name', $selectedClientName ?? '') }}">
				<input type="hidden" name="client" id="customer_id" value="{{ old('client', $selectedClientId ?? '') }}">



                {{-- <input class="form-control mb-0" placeholder="Search customer" type="search" id="search-query2" autocomplete="off">
                <input type="hidden" name="client" id="customer_id"> --}}
                <ul class="dropdown-menu w-100 show shadow-sm" id="search-results2" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
            </div>

            <div class="form-group col-md-6">
                {{ Form::label('rego', 'Reg Number', ['class' => 'form-label']) }}
                {{ Form::text('rego', $vehicle->rego, ['class' => 'form-control', 'placeholder' => 'Enter Plat Number']) }}
            </div>

            <!-- State/Emirates -->
            <div class="form-group col-md-6">
                <label for="state">State/Emirates</label>
                <select id="state" name="state" required>
                    <option value="">{{ __('Select State/Emirates') }}</option>
                    @foreach ($emirates as $key => $value)
                        <option value="{{ $key }}" {{ $vehicle->state == $key ? 'selected' : '' }}>
                            {{ $value }}</option>
                    @endforeach
                </select>
            </div>


            <!-- Vehicle Make -->
            <div class="form-group col-md-6">
                <label for="v_make">Vehicle Make</label>
                <select id="v_make" name="v_make" required>
                    <option value="">{{ __('Select Make') }}</option>
                    @foreach ($vm as $item)
                        <option value="{{ $item->id }}" {{ $vehicle->v_make == $item->id ? 'selected' : '' }}>
                            {{ $item->make_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Vehicle Model -->
            <div class="form-group col-md-6">
                {{ Form::label('vm', __('Vehicle Model'), ['class' => 'form-label']) }}
                <select name="vm" id="vm" class="form-control" required>



                    @foreach ($vmod as $id => $item)
                        <option value="{{ $id }}" {{ $vehicle->vm == $id ? 'selected' : '' }}>
                            {{ $item }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Series -->
            <div class="form-group col-md-6">
                {{ Form::label('model_series', 'Year Series', ['class' => 'form-label']) }}
                <select name="model_series" class="">
                    <option value="0000">N/A</option>
                    @for ($year = 1900; $year <= 2099; $year++)
                        <option value="{{ $year }}" {{ $vehicle->model_series == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="enginespecs">Engine Specs</label>
                <input type="text" id="enginespecs" class="form-control"
                    value="{{ $vehicle->engineSpecs->enginespecs }}" readonly />
            </div>

            <!-- Regional Specs -->
            <div class="form-group col-md-6">
                <label for="regional_specs">Regional Specs</label>
                <select id="regional_specs" name="regional_specs" class="regional_specs" required>
                    @foreach ($rs as $item)
                        <option value="{{ $item->id }}"
                            {{ $vehicle->regional_specs_id == $item->id ? 'selected' : '' }}>
                            {{ $item->title }}</option>
                    @endforeach
                </select>
            </div>
            <!-- VIN -->
            <div class="form-group col-md-6">
                {{ Form::label('vin', 'VIN', ['class' => 'form-label']) }}
                {{ Form::text('vin', $vehicle->vin, ['class' => 'form-control', 'placeholder' => 'Enter VIN']) }}
            </div>

            <!-- Odometer -->
            <div class="form-group col-md-6">
                {{ Form::label('odometer', 'Odometer', ['class' => 'form-label']) }}
                {{ Form::number('odometer', $vehicle->odometer, ['class' => 'form-control', 'placeholder' => 'Enter Odometer Reading']) }}
            </div>

            <!-- UOM -->
            <div class="form-group col-md-6">
                <label for="uom">UOM</label>
                <select id="uom" name="unit" required>
                    <option value="">{{ __('Select Unit') }}</option>
                    @foreach (['KM', 'MILES'] as $unit)
                        <option value="{{ $unit }}" {{ $vehicle->uom == $unit ? 'selected' : '' }}>
                            {{ $unit }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
<script>
    $('#close').click(function() {
        $("#customModal").modal("hide");
    });
    $(document).ready(function() {
        $('select:not(#vm)').selectize();

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
                                console.log(model);
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

            var engine = $('#enginespecs');

            console.log(2);

            if (makeId && modelId) {

                $.ajax({
                    url: '/get-engine-specification?make_id=' + makeId + '&&model_id=' +
                        modelId,
                    type: 'GET',
                    success: function(response) {
                        console.log(response);



                        if (response) {
                            engine.val(response.specification);
                        } else {
                            engine.val('no data found');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);

                        engine.val("An error occurred while fetching engine specs.");
                        alert(
                        '{{ __('An error occurred while fetching engine specs.') }}');
                    }
                });
            }
        });

    });

    $('.numberonly').keypress(function(e) {

        var charCode = (e.which) ? e.which : event.keyCode

        if (String.fromCharCode(charCode).match(/[^0-9]/g))

            return false;

    });

    $('.upper').keyup(function() {
        this.value = this.value.toLocaleUpperCase();
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
