@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/accordion/accordion.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <style>
        .pac-container {
            z-index: 10000 !important;
        }

        #map {
            height: 300px;
            width: 100%;
        }
    </style>
    <div id="alertPlaceholder"></div>

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
                            {{ Form::text('service_address1', null, ['class' => 'form-control service_address', 'id' => 'service_address1', 'placeholder' => __('service address')]) }}
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
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Booking</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">

                <a class="btn btn-primary ml-20 " href="{{ route('booking.index') }}" data-size="lg"> <i
                        class="ti-arrow-left"></i>
                    {{ __(' Back') }}
                </a>

            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')
    <!-- row opened -->

    <div class="row">
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3" id="searchGroup">
                        <h5 class="card-title mb-0 customer-header">Customer Details</h5>

                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>

                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Client Type') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" value="{{ $clientDetails->id }}" id="customer_id" />
                                <td>{{ $clientDetails->first_name }} {{ $clientDetails->last_name }}</td>

                                <td>{{ $clientDetails->client_type }}</td>
                                <td>{{ $clientDetails->email }}</td>
                                <td>+{{ preg_replace('/[^0-9]/', '', $clientDetails?->ccm) }}{{ $clientDetails->phone_number }}
                                </td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h5 class="card-title mb-0">Vehicles Details</h5>


                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>

                                    <th>{{ __('Registration Number') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Make') }}</th>
                                    <th>{{ __('Model') }}</th>


                                </tr>
                            </thead>
                            <tbody>

                                <tr>

                                    <td>{{ $vehicles->rego }} </td>
                                    <td>{{ $vehicles->name }} </td>
                                    <td>{{ @$vehicles->vehicle_makes->make_name }} </td>
                                    <td>{{ @$vehicles->vehicle_models->model_name }} </td>





                                </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header my-0">
                    <h5>Booking</h5>
                </div>
                <div class="card-body my-0">
                    <form method="POST" action="{{ route('bookingEdit') }}" accept-charset="UTF-8"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="reference">Booking ID</label>
								<p class="form-control">
									{{$bookingReference}}
								</p>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="bookingDate">Booking Date</label>
                                <p class="form-control">{{ $bookings->booking_date }} </p>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="scheduled_time" class="form-label">Booking Time</label>
								<p class="form-control mt-2">{{ $bookings->booking_time }}</p>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="requestdate">Request Date</label>
								<p class="form-control">
									{{ $bookings->requested_date }}
								</p>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="requesttime">Request Time</label>
								<p class="form-control">
									{{ $bookings->requested_time }}
								</p>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="dueDate">Due Date</label>
								<p class="form-control">
									{{ $bookings->due_date }}
								</p>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="service_group" class="form-label">Service Group</label>
                                <select class="form-control" name="service_group[]" multiple="multiple" id="service_group" disabled>
                                    <option value="">Select Service Group</option>
                                    @foreach ($servicegroups as $servicegroup)
                                        <option value="{{ $servicegroup->id }}"
                                            {{ in_array($servicegroup->id, old('service_group', $service_group ?? [])) ? 'selected' : '' }}>
                                            {{ $servicegroup->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            @php
                                $selectedSkills = is_null($bookings->skill_group)
                                    ? []
                                    : json_decode($bookings->skill_group, true);
                            @endphp

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="skill_group" class="form-label">Skill Group</label>
                                <select class="form-control bg-white" name="skill_group[]" multiple="multiple"
                                    id="skill_group" disabled>
                                    <option value="">Select Skill Group</option>
                                    @foreach ($skillGroups as $item)
                                        <option value="{{ $item->id }}"
                                            {{ in_array($item->id, $selectedSkills ?? []) ? 'selected' : '' }}>
                                            {{ $item->group_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="country" class="form-label">Country</label>
                                {{ Form::select('country', $country, $country_s, ['class' => 'form-control', 'id' => 'country', 'disabled']) }}
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control mt-2" name="city" id="city"
                                    placeholder="Enter City Name" value="{{ @$bookings->city }}" readonly>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="service_location" class="form-label">Service Address</label>
                                <input type="text" id="service_location" name="service_location"
                                    class="form-control mt-2" placeholder="Service Address"
                                    value="{{ @$bookings->service_location }}" readonly
                                    style="background-color: #e9ecef;">
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="dueDate">LandMark</label>
                                <input type="text" class="form-control" value="{{ $bookings->landmark }}"
                                    name="landmark" id="landmark">
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="description">Description</label>
                                <input class="form-control" placeholder="Enter description"{{ $bookings->description }}>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label for="exampleFormControlSelect1">Status</label>
                                <select class="form-control" name="status" readonly>
                                    <option value="Booking" {{ $bookings->status == 'Booking' ? 'selected' : '' }}>Booking
                                    </option>
                                    <option value="Completed" {{ $bookings->status == 'Completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                    <option value="Quote" {{ $bookings->status == 'Quotation' ? 'selected' : '' }}>
                                        Quotation
                                    </option>
                                    <option value="Cancelled" {{ $bookings->status == 'Cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>	

    <script>
        let items = [];
        async function fetchSimilarProducts(query, itemType) {
            const allproducts = document.getElementById('allproducts').value;
            const vehicleId = document.getElementById('selectedVehicleId').value;
            try {
                const response = await fetch(
                    `/searchproducts?search=${encodeURIComponent(query)}&itemType=${encodeURIComponent(itemType)}&vehicle_id=${encodeURIComponent(vehicleId)}&allproducts=${encodeURIComponent(allproducts)}`
                );
                const products = await response.json();
                return products;
            } catch (error) {
                console.error('Error fetching similar products:', error);
                return [];
            }
        }


        async function fetchFirstProduct(vehicleId) {
            const allproducts = document.getElementById('allproducts').value;
            try {
                const response = await fetch(
                    `/searchproducts?vehicle_id=${encodeURIComponent(vehicleId)}&allproducts=${encodeURIComponent(allproducts)}`
                );
                const products = await response.json();
                console.log(products);
                return products.length > 0 ? products[0] : null;
            } catch (error) {
                console.error('Error fetching the first product:', error);
                return null;
            }
        }


        document.getElementById('addProduct').addEventListener('click', async function() {
            const vehicleId = document.getElementById('selectedVehicleId').value;

            if (!vehicleId) {
                alert('Please select vehicle and customer first.');
                return;
            }

            const firstProduct = await fetchFirstProduct(vehicleId);
            if (firstProduct) {
                addProductToTable(firstProduct);
            }
        });

        function addProductToTable(productData) {
            const unitPrice = parseFloat(productData.price) || 0;
            const quantity = 1;
            const discountPercentage = parseFloat(productData.discount_percentage) || 0;

            const discount = (quantity * unitPrice) * (discountPercentage / 100);
            const lineTotal = (unitPrice * quantity) - discount;


            const taxPercentage = parseFloat(productData.tax) || 0;
            const taxAmount = lineTotal * (taxPercentage / 100);
            const totalAmountIncTax = lineTotal + taxAmount;

            const item = {
                id: productData.id,
                productName: productData.product_name,
                item_no: productData.item_no || '',
                description: productData.type || '',
                unitPrice,
                quantity,
                uom: productData.uom,
                uom_name: productData.uom_name || '',
                lineTotal,
                item_type: productData.item_type || '',
                discount_percentage: discountPercentage,
                warranty: productData.warranty || 'N/A',
                tax_percentage: taxPercentage,
                tax_amount: taxAmount.toFixed(2),
                total_amount_inc_tax: totalAmountIncTax.toFixed(2),
            };

            items.push(item);
            renderTable();
            updateSummary();
        }

        function renderTable() {
            const tableBody = document.getElementById('productTableBody');
            tableBody.innerHTML = '';

            items.forEach((item, index) => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
            <td>
                <button class="btn btn-danger btn-sm delete-row" data-index="${index}">Delete</button>
            </td>
            <td>
				<select class="item-type" data-index="${index}">
                    <option value="Inventory" ${item.item_type === 'Inventory' ? 'selected' : ''}>Inventory</option>
                    <option value="Service" ${item.item_type === 'Service' ? 'selected' : ''}>Service</option>
                    <option value="noninventory" ${item.item_type === 'noninventory' ? 'selected' : ''}>Non Inventory</option>
                </select>
            </td>
             <td class="item_no-cell" data-index="${index}">${item.item_no}</td>
            <td class="product-name-cell" data-index="${index}">${item.productName}</td>
           
            <td class="unit-price-cell" data-index="${index}">${item.unitPrice.toFixed(2)}</td>
            <td class="qty-cell" data-index="${index}">${item.quantity}</td>
               <td class="qty-cell" data-index="${index}">${item.uom_name}</td>
            <td class="discount-percentage-cell" data-index="${index}">${item.discount_percentage ? `${item.discount_percentage}%` : 'N/A'}</td>
            <td class="line-total-cell" data-index="${index}">${item.lineTotal.toFixed(2)}</td>
            <td class="warranty-cell" data-index="${index}">${item.warranty}</td>
         
            <td>
                <input type="number" class="tax-percentage-cell" data-index="${index}" value="${item.tax_percentage}" min="0" max="100">
            </td>
            <td class="tax-amount-cell" data-index="${index}">${item.tax_amount}</td>
            <td class="total-amount-cell" data-index="${index}">${item.total_amount_inc_tax}</td>
            <input type="hidden" class="product-id" value="${item.id}" />
            <input type="hidden" class="uom" value="${item.uom}" />
        `;
                tableBody.appendChild(newRow);
            });

            handleRowDelete();
            handleDoubleClickEdit();
            handleItemTypeChange();
        }


        function handleRowDelete() {
            document.querySelectorAll('.delete-row').forEach(function(button) {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    items.splice(index, 1);
                    renderTable();
                    updateSummary();
                });
            });
        }

        function handleItemTypeChange() {
            document.querySelectorAll('.item-type').forEach(function(select) {
                select.addEventListener('change', async function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const newItemType = this.value;

                    // Fetch the first product of the selected item type
                    const firstProduct = await fetchFirstProductByType(newItemType);

                    if (firstProduct) {
                        items[index].productName = firstProduct.product_name;
                        items[index].item_no = firstProduct.item_no || '';
                        items[index].uom_name = firstProduct.uom_name || '';
                        items[index].uom = firstProduct.uom || '';
                        items[index].unitPrice = parseFloat(firstProduct.price) || 0;
                        items[index].discount_percentage = parseFloat(firstProduct
                                .discount_percentage) ||
                            0;
                        items[index].warranty = firstProduct.warranty || 'N/A';
                        items[index].item_type = newItemType;

                        // Recalculate the line total
                        const quantity = items[index].quantity;
                        const discount = (quantity * items[index].unitPrice) * (items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * quantity) - discount;

                        renderTable();
                        updateSummary();
                    }
                });
            });
        }

        async function fetchFirstProductByType(itemType) {
            try {
                const response = await fetch(`/searchproducts?itemType=${encodeURIComponent(itemType)}`);
                const products = await response.json();
                return products.length > 0 ? products[0] : null;
            } catch (error) {
                console.error('Error fetching the first product by type:', error);
                return null;
            }
        }


        function handleDoubleClickEdit() {
            // Product name editing
            document.querySelectorAll('.product-name-cell').forEach(function(cell) {
                cell.addEventListener('click', async function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentProductName = items[index].productName;
                    const currentItemType = items[index].item_type;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentProductName;
                    input.classList.add('product-input');

                    const dropdown = document.createElement('div');
                    dropdown.classList.add('dropdown-suggestions');
                    dropdown.style.position = 'absolute';
                    dropdown.style.backgroundColor = '#fff';
                    dropdown.style.border = '1px solid #ccc';
                    dropdown.style.boxShadow = '0px 4px 6px rgba(0, 0, 0, 0.1)';
                    dropdown.style.zIndex = '1000';
                    dropdown.style.maxHeight = '200px';
                    dropdown.style.overflowY = 'auto';
                    dropdown.style.width = '30%';
                    dropdown.style.display = 'flex';
                    dropdown.style.flexDirection = 'column';

                    this.innerHTML = '';
                    this.appendChild(input);
                    this.appendChild(dropdown);
                    input.focus();

                    let selectedIndex = -1;

                    input.addEventListener('input', async function() {
                        const query = this.value.trim();
                        if (!query) {
                            dropdown.innerHTML = '';
                            return;
                        }

                        const similarProducts = await fetchSimilarProducts(query,
                            currentItemType);
                        dropdown.innerHTML = '';

                        // **Create table header**
                        const header = document.createElement('div');
                        header.style.display = 'flex';
                        header.style.fontWeight = 'bold';
                        header.style.padding = '8px';
                        header.style.backgroundColor = '#f8f9fa';
                        header.innerHTML = `<div style="width: 30%; padding-right: 10px;">PRODUCT ID</div>
                                <div style="width: 70%;">PRODUCT NAME</div>`;
                        dropdown.appendChild(header);

                        similarProducts.forEach((product, i) => {
                            const option = document.createElement('div');
                            option.classList.add('dropdown-option');
                            option.style.display = 'flex';
                            option.style.padding = '8px';
                            option.style.cursor = 'pointer';
                            option.style.borderBottom = '1px solid #eee';
                            option.innerHTML = `<div style="width: 30%; padding-right: 10px;">${product.item_no}</div>
                                    <div style="width: 70%;">${product.product_name}</div>`;

                            option.addEventListener('mouseenter', () => {
                                document.querySelectorAll(
                                        '.dropdown-option')
                                    .forEach(opt => {
                                        opt.style.backgroundColor =
                                            '#fff';
                                        opt.style.color = '#000';
                                    });
                                option.style.backgroundColor = '#007bff';
                                option.style.color = '#fff';
                                selectedIndex = i;
                            });

                            option.addEventListener('click', function() {
                                updateSelectedProduct(index, product);
                            });

                            dropdown.appendChild(option);
                        });
                    });

                    input.addEventListener('keydown', function(event) {
                        const options = dropdown.querySelectorAll('.dropdown-option');
                        if (event.key === 'ArrowDown') {
                            selectedIndex = (selectedIndex + 1) % options.length;
                            highlightOption(options, selectedIndex);
                        } else if (event.key === 'ArrowUp') {
                            selectedIndex = (selectedIndex - 1 + options.length) % options
                                .length;
                            highlightOption(options, selectedIndex);
                        } else if (event.key === 'Enter' && selectedIndex >= 0) {
                            event.preventDefault();
                            updateSelectedProduct(index, similarProducts[selectedIndex]);
                        }
                    });

                    input.addEventListener('blur', function() {
                        setTimeout(() => renderTable(), 200);
                    });

                    function highlightOption(options, index) {
                        options.forEach(opt => {
                            opt.style.backgroundColor = '#fff';
                            opt.style.color = '#000';
                        });
                        options[index].style.backgroundColor = '#007bff';
                        options[index].style.color = '#fff';
                    }

                    function updateSelectedProduct(index, product) {
                        items[index].id = product.id;
                        items[index].productName = product.product_name;
                        items[index].uom_name = product.uom_name;
                        items[index].uom = product.uom;
                        items[index].description = product.type || '';
                        items[index].unitPrice = parseFloat(product.price) || 0;
                        items[index].item_type = product.item_type || 'standard';
                        items[index].discount_percentage = parseFloat(product.discount_percentage) || 0;

                        const discount = (items[index].quantity * items[index].unitPrice) * (items[
                                index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * items[index].quantity) -
                            discount;
                        items[index].tax_percentage = parseFloat(product.tax) || 0;
                        items[index].tax_amount = items[index].lineTotal * (items[index]
                            .tax_percentage / 100);

                        items[index].total_amount_inc_tax = items[index].lineTotal + items[index]
                            .tax_amount;
                        renderTable();
                        updateSummary();
                    }
                });
            });



            document.querySelectorAll('.description-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentDescription = items[index].description;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentDescription;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newDescription = this.value.trim();
                        items[index].description = newDescription;
                        renderTable();
                    });
                });
            });


            document.querySelectorAll('.unit-price-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentUnitPrice = items[index].unitPrice;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.value = currentUnitPrice;
                    input.classList.add('unit-price-input');

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newUnitPrice = parseFloat(this.value) || 0;
                        items[index].unitPrice = newUnitPrice;


                        const discount = (items[index].quantity * newUnitPrice) * (items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (newUnitPrice * items[index].quantity) - discount;


                        const taxAmount = items[index].lineTotal * (items[index].tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(2);
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(2);

                        renderTable();
                        updateSummary();
                    });
                });
            });


            // Quantity editing
            // Quantity editing
            document.querySelectorAll('.qty-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentQuantity = items[index].quantity;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.min = '1';
                    input.value = currentQuantity;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newQuantity = parseInt(this.value) || 1;
                        items[index].quantity = newQuantity;

                        // **Recalculate Line Total**
                        const discount = (newQuantity * items[index].unitPrice) * (items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * newQuantity) - discount;

                        // **Recalculate Tax Amount**
                        const taxAmount = items[index].lineTotal * (items[index].tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(2);

                        // **Recalculate Total Amount (Including Tax)**
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(2);

                        renderTable();
                        updateSummary();
                    });
                });
            });


            // Discount percentage editing
            document.querySelectorAll('.discount-percentage-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentDiscount = items[index].discount_percentage;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.min = '0';
                    input.max = '100';
                    input.value = currentDiscount;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newDiscount = parseFloat(this.value) || 0;
                        items[index].discount_percentage = newDiscount;

                        // **Recalculate Line Total**
                        const discount = (items[index].quantity * items[index].unitPrice) * (
                            newDiscount / 100);
                        items[index].lineTotal = (items[index].unitPrice * items[index].quantity) -
                            discount;

                        // **Recalculate Tax Amount**
                        const taxAmount = items[index].lineTotal * (items[index].tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(2);

                        // **Recalculate Total Amount (Including Tax)**
                        items[index].total_amount_inc_tax = (items[index].lineTotal + taxAmount)
                            .toFixed(2);

                        renderTable();
                        updateSummary();
                    });
                });
            });


            document.querySelectorAll('.warranty-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentWarranty = items[index].warranty;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentWarranty;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newWarranty = this.value.trim();
                        items[index].warranty = newWarranty;
                        renderTable();
                    });
                });
            });

            document.querySelectorAll('.line-total-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentLineTotal = items[index].lineTotal;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.min = '0';
                    input.value = currentLineTotal.toFixed(2);

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newLineTotal = parseFloat(this.value) || 0;
                        items[index].lineTotal = newLineTotal;

                        // Recalculate tax and total amount including tax
                        const taxPercentage = items[index].tax_percentage || 0;
                        const taxAmount = newLineTotal * (taxPercentage / 100);
                        items[index].tax_amount = taxAmount.toFixed(2);
                        items[index].total_amount_inc_tax = (newLineTotal + taxAmount).toFixed(2);

                        renderTable();
                        updateSummary();
                    });
                });
            });
            document.querySelectorAll('.tax-percentage-cell').forEach(input => {
                input.addEventListener('input', (event) => {
                    const index = event.target.getAttribute('data-index');
                    const newTaxPercentage = parseFloat(event.target.value) || 0;
                    const input = document.createElement('input');
                    input.type = 'text';

                    items[index].tax_percentage = newTaxPercentage;


                    const taxAmount = (items[index].lineTotal * newTaxPercentage) / 100;
                    items[index].tax_amount = taxAmount;
                    items[index].total_amount_inc_tax = items[index].lineTotal - taxAmount;


                    renderTable();
                });
            });
        }

        function updateSummary() {
            let subtotalExclVAT = 0;
            let discountAmount = 0;
            let totalVAT = 0;

            items.forEach(item => {
                subtotalExclVAT += (parseFloat(item.unitPrice) || 0) * (parseInt(item.quantity) || 1);
                discountAmount += ((parseFloat(item.unitPrice) || 0) * (parseInt(item.quantity) || 1)) * (
                    parseFloat(
                        item.discount_percentage) / 100 || 0);
                totalVAT += parseFloat(item.tax_amount) || 0;
            });

            let totalExclVAT = subtotalExclVAT - discountAmount;
            let totalInclVAT = totalExclVAT + totalVAT;

            // Update UI
            document.getElementById('subtotalExclVAT').innerText = subtotalExclVAT.toFixed(2);
            document.getElementById('discountAmount').innerText = discountAmount.toFixed(2);
            document.getElementById('totalExclVAT').innerText = totalExclVAT.toFixed(2);
            document.getElementById('totalVAT').innerText = totalVAT.toFixed(2);
            document.getElementById('totalInclVAT').innerText = totalInclVAT.toFixed(2);
        }
        // Update summary when freight input changes
        document.getElementById('freight').addEventListener('input', function() {
            updateSummary();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#service_group').select2({
                placeholder: "Select Service Group",
                allowClear: true,
                width: '100%'
            });
            $('#skill_group').select2({
                placeholder: "Select Skill Group",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <script>
        $('.mapmodal_close').on('click', function() {
            $('#mapmodal').modal('hide');
        });

        $('#mapmodal_confirm').on('click', function() {
            $('#mapmodal').modal('hide');

            document.getElementById('service_location').value = document.getElementById('service_address1').value;
            document.getElementById('city').value = document.getElementById('service_city1').value;
            // document.getElementById('service_state').value = document.getElementById('service_state1').value;
            // document.getElementById('service_country').value = document.getElementById('service_country1').value;
            // document.getElementById('service_zip_code').value = document.getElementById('service_zip_code1').value;
        });

        $('.numberonly').keypress(function(e) {

            var charCode = (e.which) ? e.which : event.keyCode

            if (String.fromCharCode(charCode).match(/[^0-9]/g))

                return false;

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
            document.getElementById('service_address1').value = document.getElementById('service_location').value;
            document.getElementById('service_city1').value = document.getElementById('city').value;

            initAutocomplete_map();
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

        var country_arr = ["AD=42.546245,1.601554",
            "AE=23.424076,53.847818",
            "AF=33.93911,67.709953",
            "AG=17.060816,-61.796428",
            "AI=18.220554,-63.068615",
            "AL=41.153332,20.168331",
            "AM=40.069099,45.038189",
            "AN=12.226079,-69.060087",
            "AO=-11.202692,17.873887",
            "AQ=-75.250973,-0.071389",
            "AR=-38.416097,-63.616672",
            "AS=-14.270972,-170.132217",
            "AT=47.516231,14.550072",
            "AU=-25.274398,133.775136",
            "AW=12.52111,-69.968338",
            "AZ=40.143105,47.576927",
            "BA=43.915886,17.679076",
            "BB=13.193887,-59.543198",
            "BD=23.684994,90.356331",
            "BE=50.503887,4.469936",
            "BF=12.238333,-1.561593",
            "BG=42.733883,25.48583",
            "BH=25.930414,50.637772",
            "BI=-3.373056,29.918886",
            "BJ=9.30769,2.315834",
            "BM=32.321384,-64.75737",
            "BN=4.535277,114.727669",
            "BO=-16.290154,-63.588653",
            "BR=-14.235004,-51.92528",
            "BS=25.03428,-77.39628",
            "BT=27.514162,90.433601",
            "BV=-54.423199,3.413194",
            "BW=-22.328474,24.684866",
            "BY=53.709807,27.953389",
            "BZ=17.189877,-88.49765",
            "CA=56.130366,-106.346771",
            "CC=-12.164165,96.870956",
            "CD=-4.038333,21.758664",
            "CF=6.611111,20.939444",
            "CG=-0.228021,15.827659",
            "CH=46.818188,8.227512",
            "CI=7.539989,-5.54708",
            "CK=-21.236736,-159.777671",
            "CL=-35.675147,-71.542969",
            "CM=7.369722,12.354722",
            "CN=35.86166,104.195397",
            "CO=4.570868,-74.297333",
            "CR=9.748917,-83.753428",
            "CU=21.521757,-77.781167",
            "CV=16.002082,-24.013197",
            "CX=-10.447525,105.690449",
            "CY=35.126413,33.429859",
            "CZ=49.817492,15.472962",
            "DE=51.165691,10.451526",
            "DJ=11.825138,42.590275",
            "DK=56.26392,9.501785",
            "DM=15.414999,-61.370976",
            "DO=18.735693,-70.162651",
            "DZ=28.033886,1.659626",
            "EC=-1.831239,-78.183406",
            "EE=58.595272,25.013607",
            "EG=26.820553,30.802498",
            "EH=24.215527,-12.885834",
            "ER=15.179384,39.782334",
            "ES=40.463667,-3.74922",
            "ET=9.145,40.489673",
            "FI=61.92411,25.748151",
            "FJ=-16.578193,179.414413",
            "FK=-51.796253,-59.523613",
            "FM=7.425554,150.550812",
            "FO=61.892635,-6.911806",
            "FR=46.227638,2.213749",
            "GA=-0.803689,11.609444",
            "GB=55.378051,-3.435973",
            "GD=12.262776,-61.604171",
            "GE=42.315407,43.356892",
            "GF=3.933889,-53.125782",
            "GG=49.465691,-2.585278",
            "GH=7.946527,-1.023194",
            "GI=36.137741,-5.345374",
            "GL=71.706936,-42.604303",
            "GM=13.443182,-15.310139",
            "GN=9.945587,-9.696645",
            "GP=16.995971,-62.067641",
            "GQ=1.650801,10.267895",
            "GR=39.074208,21.824312",
            "GS=-54.429579,-36.587909",
            "GT=15.783471,-90.230759",
            "GU=13.444304,144.793731",
            "GW=11.803749,-15.180413",
            "GY=4.860416,-58.93018",
            "GZ=31.354676,34.308825",
            "HK=22.396428,114.109497",
            "HM=-53.08181,73.504158",
            "HN=15.199999,-86.241905",
            "HR=45.1,15.2",
            "HT=18.971187,-72.285215",
            "HU=47.162494,19.503304",
            "ID=-0.789275,113.921327",
            "IE=53.41291,-8.24389",
            "IL=31.046051,34.851612",
            "IM=54.236107,-4.548056",
            "IN=20.593684,78.96288",
            "IO=-6.343194,71.876519",
            "IQ=33.223191,43.679291",
            "IR=32.427908,53.688046",
            "IS=64.963051,-19.020835",
            "IT=41.87194,12.56738",
            "JE=49.214439,-2.13125",
            "JM=18.109581,-77.297508",
            "JO=30.585164,36.238414",
            "JP=36.204824,138.252924",
            "KE=-0.023559,37.906193",
            "KG=41.20438,74.766098",
            "KH=12.565679,104.990963",
            "KI=-3.370417,-168.734039",
            "KM=-11.875001,43.872219",
            "KN=17.357822,-62.782998",
            "KP=40.339852,127.510093",
            "KR=35.907757,127.766922",
            "KW=29.31166,47.481766",
            "KY=19.513469,-80.566956",
            "KZ=48.019573,66.923684",
            "LA=19.85627,102.495496",
            "LB=33.854721,35.862285",
            "LC=13.909444,-60.978893",
            "LI=47.166,9.555373",
            "LK=7.873054,80.771797",
            "LR=6.428055,-9.429499",
            "LS=-29.609988,28.233608",
            "LT=55.169438,23.881275",
            "LU=49.815273,6.129583",
            "LV=56.879635,24.603189",
            "LY=26.3351,17.228331",
            "MA=31.791702,-7.09262",
            "MC=43.750298,7.412841",
            "MD=47.411631,28.369885",
            "ME=42.708678,19.37439",
            "MG=-18.766947,46.869107",
            "MH=7.131474,171.184478",
            "MK=41.608635,21.745275",
            "ML=17.570692,-3.996166",
            "MM=21.913965,95.956223",
            "MN=46.862496,103.846656",
            "MO=22.198745,113.543873",
            "MP=17.33083,145.38469",
            "MQ=14.641528,-61.024174",
            "MR=21.00789,-10.940835",
            "MS=16.742498,-62.187366",
            "MT=35.937496,14.375416",
            "MU=-20.348404,57.552152",
            "MV=3.202778,73.22068",
            "MW=-13.254308,34.301525",
            "MX=23.634501,-102.552784",
            "MY=4.210484,101.975766",
            "MZ=-18.665695,35.529562",
            "NA=-22.95764,18.49041",
            "NC=-20.904305,165.618042",
            "NE=17.607789,8.081666",
            "NF=-29.040835,167.954712",
            "NG=9.081999,8.675277",
            "NI=12.865416,-85.207229",
            "NL=52.132633,5.291266",
            "NO=60.472024,8.468946",
            "NP=28.394857,84.124008",
            "NR=-0.522778,166.931503",
            "NU=-19.054445,-169.867233",
            "NZ=-40.900557,174.885971",
            "OM=21.512583,55.923255",
            "PA=8.537981,-80.782127",
            "PE=-9.189967,-75.015152",
            "PF=-17.679742,-149.406843",
            "PG=-6.314993,143.95555",
            "PH=12.879721,121.774017",
            "PK=30.375321,69.345116",
            "PL=51.919438,19.145136",
            "PM=46.941936,-56.27111",
            "PN=-24.703615,-127.439308",
            "PR=18.220833,-66.590149",
            "PS=31.952162,35.233154",
            "PT=39.399872,-8.224454",
            "PW=7.51498,134.58252",
            "PY=-23.442503,-58.443832",
            "QA=25.354826,51.183884",
            "RE=-21.115141,55.536384",
            "RO=45.943161,24.96676",
            "RS=44.016521,21.005859",
            "RU=61.52401,105.318756",
            "RW=-1.940278,29.873888",
            "SA=23.885942,45.079162",
            "SB=-9.64571,160.156194",
            "SC=-4.679574,55.491977",
            "SD=12.862807,30.217636",
            "SE=60.128161,18.643501",
            "SG=1.352083,103.819836",
            "SH=-24.143474,-10.030696",
            "SI=46.151241,14.995463",
            "SJ=77.553604,23.670272",
            "SK=48.669026,19.699024",
            "SL=8.460555,-11.779889",
            "SM=43.94236,12.457777",
            "SN=14.497401,-14.452362",
            "SO=5.152149,46.199616",
            "SR=3.919305,-56.027783",
            "ST=0.18636,6.613081",
            "SV=13.794185,-88.89653",
            "SY=34.802075,38.996815",
            "SZ=-26.522503,31.465866",
            "TC=21.694025,-71.797928",
            "TD=15.454166,18.732207",
            "TF=-49.280366,69.348557",
            "TG=8.619543,0.824782",
            "TH=15.870032,100.992541",
            "TJ=38.861034,71.276093",
            "TK=-8.967363,-171.855881",
            "TL=-8.874217,125.727539",
            "TM=38.969719,59.556278",
            "TN=33.886917,9.537499",
            "TO=-21.178986,-175.198242",
            "TR=38.963745,35.243322",
            "TT=10.691803,-61.222503",
            "TV=-7.109535,177.64933",
            "TW=23.69781,120.960515",
            "TZ=-6.369028,34.888822",
            "UA=48.379433,31.16558",
            "UG=1.373333,32.290275",
            "UM=0,0",
            "US=37.09024,-95.712891",
            "UY=-32.522779,-55.765835",
            "UZ=41.377491,64.585262",
            "VA=41.902916,12.453389",
            "VC=12.984305,-61.287228",
            "VE=6.42375,-66.58973",
            "VG=18.420695,-64.639968",
            "VI=18.335765,-64.896335",
            "VN=14.058324,108.277199",
            "VU=-15.376706,166.959158",
            "WF=-13.768752,-177.156097",
            "WS=-13.759029,-172.104629",
            "XK=42.602636,20.902977",
            "YE=15.552727,48.516388",
            "YT=-12.8275,45.166244",
            "ZA=-30.559482,22.937506",
            "ZM=-13.133897,27.849332",
            "ZW=-19.015438,29.154857"
        ];
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
        $(document).ready(function() {
            $('#customer_id').select2({
                placeholder: 'Select your customer',
                allowClear: true
            });

            $('#customer_id').on('change', function() {
                var customerId = this.value;
                if (customerId) {
                    fetch(`/workorder-client/fetch/api/${customerId}`)
                        .then(response => response.json())
                        .then(data => {

                            $('#customer_name').val(data.full_name);
                            $('#phone').val(data.phone);
                            $('#email').val(data.email);
                            $('#type').val(data.type);


                            $('#country').val(data.country_id).trigger(
                                'change');
                        })
                        .catch(error => console.error('Error:', error));
                } else {

                    $('#customer_name').val('');
                    $('#phone').val('');
                    $('#email').val('');
                    $('#type').val('');
                    $('#country').val('').trigger('change');
                }
            });
        });


        $('#service_location').on('input', function() {
            var address = $(this).val();
            if (address.length > 1) {
                $.ajax({
                    url: '/fetch-place',
                    method: 'GET',
                    data: {
                        address: address
                    },
                    success: function(data) {
                        var suggestions = $('#addressSuggestions');
                        suggestions.empty();
                        if (data.candidates && data.candidates.length > 0) {
                            suggestions.show();
                            data.candidates.forEach(function(place) {
                                var listItem = $('<li class="list-group-item"></li>')
                                    .text(place.formatted_address)
                                    .data('address', place.formatted_address)
                                    .on('click', function() {
                                        $('#service_location').val($(this).data(
                                            'address'));
                                        suggestions.hide();
                                    });
                                suggestions.append(listItem);
                            });
                        } else {
                            suggestions.hide();
                        }
                    },
                    error: function() {
                        alert("Error fetching address.");
                    }
                });
            } else {
                $('#addressSuggestions').hide();
            }
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#service_location').length) {
                $('#addressSuggestions').hide();
            }
        });
    </script>

    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO1Dw9T3wDRjN2RyrGLE2XTG86x46cIUc&loading=async&libraries=places">
    </script>

    <script>
        $(document).ready(function() {
            // Function to get the current booking_id value
            function getBookingId() {
                return $("#booking_id").val();
            }

            // Load comments based on the booking_id
            function loadComments() {
                let bookingId = getBookingId(); // Dynamically fetch the booking_id
                $.ajax({
                    url: "{{ route('journal.fetch') }}",
                    type: "GET",
                    data: {
                        booking_id: bookingId
                    },
                    dataType: "json",
                    success: function(data) {
                        let journalHtml = '';
                        $.each(data, function(key, journal) {
                            journalHtml += `
                            <div class="d-flex align-items-start mb-3">
                                <div>
                                    <small class="text-muted d-block">${journal.user.full_name} - ${new Date(journal.created_at).toLocaleString()}</small>
                                    <p class="mb-0">${journal.comment}</p>
                                </div>
                            </div>
                        `;
                        });
                        $("#journal-list").html(journalHtml);
                    },
                    error: function(xhr) {
                        $("#error-message").text(xhr.responseJSON.error);
                    }
                });
            }

            // Trigger the loadComments function on page load
            loadComments();

            // Click handler for sending a new comment
            $("#send-comment").click(function() {
                let comment = $("#comment-input").val();
                if (comment.trim() !== "") {
                    let bookingId = getBookingId(); // Dynamically fetch the booking_id
                    $.ajax({
                        url: "{{ route('journal.store') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            booking_id: bookingId,
                            comment: comment
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#comment-input").val(""); // Clear the input field
                                loadComments
                                    (); // Reload the comments after a successful submission
                            }
                        },
                        error: function(xhr) {
                            $("#error-message").text(xhr.responseJSON.error);
                        }
                    });
                }
            });
        });
    </script>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--- Internal Accordion Js -->
    <script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/accordion.js') }}"></script>
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@endsection
