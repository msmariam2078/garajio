<style>
    .custom {
        width: 800px;
        border-radius: 20px;
        padding: 20px;
    }
</style>
<script></script>

<div class="modal-body ms-5" id="customModal">

    <form method="POST" action="{{ route('warrentyextend.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
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
            <div class="form-group col-md-6 ">
                {{ Form::label('vehicle_id', __('Equipment'), ['class' => 'form-label mb-3']) }}


                {{ Form::select('vehicle_id', $vehicles, null, ['class' => 'form-control ', 'id' => 'vehicle']) }}

            </div>

            <div class="form-group col-md-6 mb-4">
                {{ Form::label('purchase_date', __('Purchase Date'), ['class' => 'form-label mb-3']) }}

                {{ Form::date('purchase_date', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('extend_start_date', __('Extend Warranty Start Date'), ['class' => 'form-label ']) }}


                {{ Form::date('extend_start_date', null, ['class' => 'form-control', 'id' => 'start', 'required' => 'required']) }}

            </div>
            

            <div class="form-group col-md-6 mb-4">
                {{ Form::label('duration', __('Duration (Months)'), ['class' => 'form-label mb-3']) }}

                {{ Form::text('duration', null, ['class' => 'form-control ', 'placeholder' => __(' '), 'id' => 'period', 'required' => 'required']) }}

            </div>
            <div class="form-group col-md-6 ">
                {{ Form::label('extend_end_date', __('Extend Warranty End Date'), ['class' => 'form-label ']) }}


                {{ Form::date('extend_end_date', null, ['class' => 'form-control', 'id' => 'end', 'required' => 'required']) }}

            </div>
            <div class="form-group col-md-6 mb-4">
                {{ Form::label('price', __('Price'), ['class' => 'form-label mb-3']) }}


                {{ Form::text('price', null, ['class' => 'form-control', 'required' => 'required']) }}

            </div>
            <div class="form-group col-md-6 mb-4">
                {{ Form::label('coverage_type', __('Coverage type'), ['class' => 'form-label mb-3']) }}


                {{ Form::select('coverage_type', ['parts' => 'parts'], null, ['class' => 'form-control ']) }}

            </div>

            <div class="form-group col-md-6 mb-4">
                {{ Form::label('status', __('Status'), ['class' => 'form-label mb-3']) }}


                {{ Form::select('status', [0 => 'unpaid', 1 => 'paid'], null, ['class' => 'form-control ']) }}

            </div>
        </div>










        <div class="modal-footer">
            <button type="button" class="btn btn-secondary mapmodal_close" id="close">Cancel</button>
            <button type="submit" class="btn btn-primary" id="mapmodal_confirm">Confirm</button>
        </div>

    </form>
</div>

<script>
    function addMonths(date, months) {
        date.setMonth(date.getMonth() + months);

        return date;
    }
    function formatDateForInput(dateString) {
    const parts = dateString.split('/');
    if (parts.length === 3) {
        return `${parts[2]}-${parts[1]}-${parts[0]}`; // yyyy-MM-dd
    }
    return dateString;
}


    $('#close').click(function() {
        $('#customModal').modal('hide');
    });
    $(document).ready(function() {
        let start = document.getElementById("start");
        let warranty_period = document.getElementById("period");
       
        warranty_period.addEventListener("change", function() {
       document.getElementById('end').value='';
       
            
            if (warranty_period.value > 0) {
                let end = addMonths(new Date(start.value), parseInt(warranty_period.value));
                  const endDateString = end.toISOString().split('T')[0];
                console.log(endDateString);
                   document.getElementById('end').value = endDateString;

            }
        });

        //    $('#customer').on('change', function() {
        //        var customer = $('#customer').val();
        //        var vehicle = $('#vehicle');


        //        console.log(2);

        //        if (customer ) {

        //            $.ajax({
        //                url: 'get-vehicle?client_id=' + customer ,
        //                type: 'GET',
        //                success: function(response) {

        //                 if (response) {
        //                         console.log(response);
        //                               // Clear and populate the Vehicle Model dropdown
        //                     $('#vehicle').empty().append(
        //                         '<option value="">Select Vehicle</option>');

        //                     response.forEach(function(vehicle) {
        //                         $('#vehicle').append('<option value="' + vehicle.id + '">' +
        //                             vehicle.rego+ '</option>');
        //                     });
        //                     } else {
        //                         product.val('no data found');
        //                     }
        //                 },
        //                 error: function(xhr, status, error) {
        //                     console.error(xhr);

        //                     product.val("An error occurred while fetching product.");
        //                     alert('{{ __('An error occurred while fetching product.') }}');
        //                 }
        //             });
        //         }
        //  });
    });
    $(document).ready(function() {

        $('#vehicle').on('change', function() {
            var customer = $('#customer_id').val();
            var vehicle = $(this).val();

           
            var product_id = $('#product_id');
            console.log(2);

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
