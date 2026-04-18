<div class="modal-body ms-5" id="closeModal">

    <form method="POST" action="{{ route('warrentyitems.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="form-group col-md-6 position-relative">
                <label for="search-query2">Customer</label>

                <input class="form-control mb-0" placeholder="Search customer" type="search" id="search-query2"
                    autocomplete="off" value="{{ old('client_name', $selectedClientName ?? '') }}">
                <input type="hidden" name="customer_id" id="customer_id"
                    value="{{ old('client', $selectedClientId ?? '') }}">



                {{-- <input class="form-control mb-0" placeholder="Search customer" type="search" id="search-query2" autocomplete="off">
                          <input type="hidden" name="customer_id" id="customer_id"> --}}
                <ul class="dropdown-menu w-100 show shadow-sm" id="search-results2"
                    style="display: none; max-height: 200px; overflow-y: auto;"></ul>
            </div>


            <div class="form-group col-md-6 mb-4">
                {{ Form::label('product_id', __('Product'), ['class' => 'form-label mb-3']) }}
                <select class="form-control " name="product_id" id="product_id">
                   
                </select>
            </div>
            <div class="form-group col-md-6 mb-4">
                {{ Form::label('vehicle_id', __('Equipment'), ['class' => 'form-label mb-3']) }}


                {{ Form::select('vehicle_id', $vehicles, null, ['class' => 'form-control text-dark', 'id' => 'vehicle']) }}

            </div>

            <div class="form-group col-md-6 mb-4">
                {{ Form::label('claim_date', __('Claim Date'), ['class' => 'form-label mb-3']) }}

                {{ Form::date('claim_date', \Carbon\Carbon::today(), ['class' => 'form-control', 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-6 mb-4">
                {{ Form::label('status', __('Status'), ['class' => 'form-label mb-3']) }}


                {{ Form::select('status', [1 => 'Active', 2 => 'Already Claimed'], null, ['class' => 'form-control ']) }}

            </div>

            <div class="form-group col-md">
                {{ Form::label('issued_description', __('Issued Description'), ['class' => 'form-label mb-3']) }}

                {{ Form::textarea('issued_description', null, ['class' => 'form-control ', 'placeholder' => __(' '), 'rows' => 3, 'required' => 'required']) }}

            </div>
            <div class="form-group col-md-6">
                {{ Form::label('issued_proof', __('Issued Proof'), ['class' => 'form-label']) }}
                <input type="file" name='profile_picture' class="dropify" data-height="100" accept='image/*' />


            </div>


            <div class="form-group col-md-6 mb-4">
                {{ Form::label('service_center', __('Service center'), ['class' => 'form-label mb-3']) }}


                <select name="service_center" class="form-control">
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>





            <div class="form-group col-md-6 mb-4">
                {{ Form::label('repair_date', __('Repair date'), ['class' => 'form-label mb-3']) }}

                {{ Form::date('repair_date', null, ['class' => 'form-control ', 'placeholder' => __(' '), 'required' => 'required']) }}

            </div>
            <div class="form-group col-md-6 mb-4">
                {{ Form::label('claim_solution', __('Claim solution'), ['class' => 'form-label mb-3']) }}

                {{ Form::textarea('claim_solution', null, ['class' => 'form-control ', 'rows' => 2, 'placeholder' => __(' '), 'required' => 'required']) }}

            </div>
            <div class="form-group col-md-6 mb-4">
                {{ Form::label('notes', __('Notes'), ['class' => 'form-label mb-3']) }}

                {{ Form::textarea('notes', null, ['class' => 'form-control ', 'rows' => 2, 'placeholder' => __(' ')]) }}

            </div>


        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary mapmodal_close" id="close">Cancel</button>
            <button type="submit" class="btn btn-primary" id="mapmodal_confirm">Confirm</button>
        </div>
    </form>
</div>



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

            if (id) {
                $.ajax({
                    url: 'get-vehicle?client_id=' + id,
                    type: 'GET',
                    success: function(response) {

                        if (response) {
                            console.log(response);
                            // Clear and populate the Vehicle Model dropdown
                            $('#vehicle').empty().append(
                                '<option value="">Select Vehicle</option>');

                            response.forEach(function(vehicle) {
                                $('#vehicle').append('<option value="' + vehicle
                                    .id + '">' +
                                    vehicle.rego + '</option>');
                            });
                        } else {
                            product.val('no data found');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);

                        product.val("An error occurred while fetching product.");
                        alert('{{ __('An error occurred while fetching product.') }}');
                    }
                });
            }
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

<script>
    $(document).ready(function() {

        $('#close').click(function() {
            $('#customModal').modal('hide');
        });

    });

    $(document).ready(function() {


        $('#vehicle').on('change', function() {
            var customer = $('#customer_id').val();
            var vehicle = $(this).val();

            
            var product_id = $('#product_id');
            

            if (customer && vehicle) {

                $.ajax({
                    url: 'toggle-warranty/' + customer + '/' + vehicle,
                    type: 'GET',
                    success: function(models) {
                // Clear and populate the Vehicle Model dropdown
                $('#product_id').empty().append(
                    '<option value="">Select Product</option>');
                    console.log(models.products);

                models.products.forEach(function(model) {
                    $('#product_id').append('<option value="' + model.id + '">' +
                        model.product_name + '</option>');
                });
            }
                    ,
                    error: function(xhr, status, error) {
                        console.error(xhr);

                        product_id.val("An error occurred while fetching product.");
                        alert('{{ __('An error occurred while fetching product.') }}');
                    }
                });
            }
        });
    });
</script>
