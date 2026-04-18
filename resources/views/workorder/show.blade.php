@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/accordion/accordion.css') }}" rel="stylesheet" />
@section('page-header')
    <style>
        .custom-modal .modal-dialog {
            max-width: 90%;
            /* Adjust to your preference */
        }

        .custom-modal .modal-content {
            width: 100%;
        }

        .pac-container {
            z-index: 10000 !important;
        }

        #map {
            height: 300px;
            width: 100%;
        }

        .form-check-label {
            font-size: 14px;
            color: #333;
            padding-left: 10px;
        }

        .pac-container {
            z-index: 10000 !important;
        }

        #map {
            height: 300px;
            width: 100%;
        }
    </style>

    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">WorkOrder view</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

            <div class="mb-3 mb-xl-0">
                @if (Gate::check('create client'))
                    <a class="btn btn-primary ml-20 " href="{{ route('workorder.index') }}" data-size="lg"> <i
                            class="ti-arrow-left"></i>
                        {{ __('Back') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
@endsection
@section('content')
    @include('messages_alert')
    <div class="row">
        <!-- Customer Details -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0 customer-header">Workorder Details</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="reference">WorkOrder Date</label>
                            <p class="form-control">{{ $workOrder->created_date }} </p>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="reference">WorkOrder Number</label>
                            <p class="form-control">{{ $workOrder->id }} </p>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label for="status">Status</label>
                            <p class="form-control">{{ $workOrder->status }}</p>
                        </div>
                        @if ($appointment)
                            @if ($appointment->from_date)
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label for="status">Appointment Date</label>
                                    <p class="form-control">{{ $appointment->from_date ?? '' }} </p>
                                </div>
                            @endif
                            @if ($appointment->from_time && $appointment->to_time)
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label for="status">Appointment Time</label>
                                    <p class="form-control">
                                        {{ \Carbon\Carbon::create($appointment->from_time)->format('h:i:s A') ?? '' }}-{{ \Carbon\Carbon::create($appointment->to_time)->format('h:i:s A') ?? '' }}
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0 customer-header">Customer Details</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        @foreach ($clients as $kay => $clientDetails)
                            <div class="col-xl-12 d-flex align-items-center">
                                <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Name') }}:</label>
                                <p class="form-control">{{ $clientDetails->first_name }} {{ $clientDetails->last_name }}
                                </p>
                            </div>
                            <div class="col-xl-12 d-flex align-items-center">
                                <label class="form-label me-3 text-end"
                                    style="width: 30%;">{{ __('Client Type') }}:</label>
                                <p class="form-control">{{ $clientDetails->client_type }} </p>
                            </div>
                            <div class="col-xl-12 d-flex align-items-center">
                                <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Email') }}:</label>
                                <p class="form-control">{{ $clientDetails->email }}</p>
                            </div>
                            <div class="col-xl-12 d-flex align-items-center">
                                <label class="form-label me-3 text-end" style="width: 30%;">{{ __('Phone') }}:</label>
                                <p class="form-control">
                                    +{{ preg_replace('/[^0-9]/', '', $clientDetails->ccm) . $clientDetails->phone_number }}
                                </p>
                            </div>
                        @endforeach
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
                <div class="card-body pb-2">
                    <div class="row">
                        @foreach ($vehicles as $key => $vehicle)
                            <div class="col-6 mt-0">
                                <label class="form-label me-3 text-end">{{ __('Vehicle ID') }}:</label>
                                <p class="form-control">{{ $vehicle->id }}</p>
                            </div>
                            <div class="col-6 mt-0">
                                <label class="form-label me-3 text-end">{{ __('Registration Number') }}:</label>
                                <p class="form-control">{{ @$vehicle->rego }}</p>
                            </div>
                            <div class="col-6">
                                <label class="form-label me-3 text-end">{{ __('Make') }}:</label>
                                <select class="form-control" name="v_make" id="v_make" required disabled
                                    {{ Auth::user()->type != 'technician' ? '' : 'disabled' }}
                                    {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'disabled' : '' }}>
                                    @foreach ($vm as $id => $make)
                                        <option value="{{ $id }}"
                                            {{ $vehicle->vehicle_makes?->id == $id ? 'selected' : '' }}>
                                            {{ $make }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('Model') }}:</label>
                                <select class="form-control" name="v_model" id="v_model" required disabled
                                    {{ Auth::user()->type != 'technician' ? '' : 'disabled' }}
                                    {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'disabled' : '' }}>
                                    @foreach ($vmod as $id => $make)
                                        <option value="{{ $id }}"
                                            {{ $vehicle->vehicle_models->id == $id ? 'selected' : '' }}>
                                            {{ $make }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mt-3">
                                <label class="form-label">{{ __('Year Series') }}:</label>
                                <select id="model_series" class="form-control" disabled
                                    {{ Auth::user()->type != 'technician' ? '' : 'disabled' }}
                                    {{ in_array($workOrder->status, ['Invoiced', 'Paid']) ? 'disabled' : '' }}>
                                    <option value="0000">N/A
                                    <option>
                                        @for ($year = 1900; $year <= 2099; $year++)
                                    <option value="{{ $year }}"
                                        {{ $vehicle->model_series == $year ? 'selected' : '' }}>{{ $year }}
                                    </option>
                        @endfor
                        </select>
                    </div>
                    <div class="col-4 mt-3">
                        <label class="form-label">{{ __('Vin') }}:</label>
                        <p class="form-control">{{ @$vehicle->vin }} </p>
                    </div>
                    <div class="col-4 mt-3">
                        <label class="form-label">{{ __('Odometer') }}:</label>
                        <p class="form-control">{{ @$vehicle->odometer }}</p>
					</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.getElementById('toggleWarranty').addEventListener('click', function() {
            const warrantyStatus = document.getElementById('warrantystatus');
            const warrantyDetailsRow = document.getElementById('warranty-details-row');

            if (warrantyStatus.value === "0") {
                warrantyStatus.value = "1";
                warrantyDetailsRow.classList.remove('hidden');
            } else {
                warrantyStatus.value = "0";
                warrantyDetailsRow.classList.add('hidden');
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const techniciansTbody = document.getElementById("technicians-tbody");

            function fetchTechnicians(checkinCheckout) {
                const workOrderId = @json($workOrder->id);
                const skillGroup = document.getElementById("skill_group").value;
                const url =
                    `/gettechnicianallocate/${workOrderId}?checkin_checkout=${checkinCheckout}&skill_group=${skillGroup}`;

                console.log("Fetching technicians with URL:", url);

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        techniciansTbody.innerHTML = html;
                    })
                    .catch(error => {
                        console.error("Error fetching technicians:", error);
                        techniciansTbody.innerHTML = `
                    <tr>
                    
                        <td colspan="6" class="text-center text-danger">Failed to load data</td>
                    </tr>`;
                    });
            }


            window.handleButtonClick = function(value) {
                console.log("Button clicked. Sending value:", value);
                fetchTechnicians(value);
            };

            window.handleChange = function() {
                console.log("Skill group changed, fetching technicians.");
                fetchTechnicians(0);
            };

            fetchTechnicians(0);
        });
    </script>

    <script>
        $('#modaldemo1').on('shown.bs.modal', function() {
            $('#skill_group').select2({
                placeholder: "Select Skill Group",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('#default-technicians').show();
            $('#other-content').hide();


            $('#switch-lg').change(function() {
                if ($(this).is(':checked')) {

                    $('#default-technicians').hide();
                    $('#other-content').show();
                } else {

                    $('#default-technicians').show();
                    $('#other-content').hide();
                }
            });



        });
    </script>

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

        async function fetchQuotProduct() {

            const workOrderId = @json($workOrder->id);

            try {
                const response = await fetch(
                    `/getquotation/workorder?workorder=${workOrderId}`
                );
                const products = await response.json();
                console.log(products);
                return products.length > 0 ? products : null;
            } catch (error) {
                console.error('Error fetching the quote products:', error);
                return null;
            }
        }

        async function fetchFirstProduct(vehicleId) {
            const allproducts = document.getElementById('allproducts').value;
            try {
                const response = await fetch(
                    `/searchproducts?vehicle_id=${encodeURIComponent(vehicleId)}&allproducts=${encodeURIComponent(allproducts)}`
                );
                const products = await response.json();

                return products.length > 0 ? products[0] : null;
            } catch (error) {
                console.error('Error fetching the first product:', error);
                return null;
            }
        }

        async function fetchAndShowFirstProduct() {

            // if (items.length === 0) {
            const quotProducts = await fetchQuotProduct();

            if (quotProducts.length > 0) {

                for (let i = 0; i < quotProducts.length; i++) {
                    addProductToTable(quotProducts[i], 1);
                }
            }

        }

        // window.onload = function() {
        //     console.log('load');
        fetchAndShowFirstProduct();


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

        function addProductToTable(productData, exist = 0) {
            const unitPrice = parseFloat(productData.price) || productData.unit_price || 0;
            const quantity = productData.qty;
            const discountPercentage = parseFloat(productData.discount_percentage) || parseFloat(productData.gstprice) || 0;

            const discount = (quantity * unitPrice) * (discountPercentage / 100);
            const lineTotal = (unitPrice * quantity) - discount || productData.linetotal;
            const id = productData.id || productData.product_id || null;

            const taxPercentage = parseFloat(productData.tax) || parseFloat(productData.taxpercentage) || 0;
            const taxAmount = lineTotal * (taxPercentage / 100);
            const totalAmountIncTax = lineTotal + taxAmount;

            const item = {
                id: id,
                productName: productData.product_name,
                item_no: productData.item_no || '',
                description: productData.type || productData.description || '',
                unitPrice,
                quantity,
                uom: productData.uom,
                uom_name: productData.uom_name || productData.uom || '',
                lineTotal,
                item_type: productData.item_type || '',
                discount_percentage: discountPercentage,
                warranty: productData.warrenty || 'N/A',
                location: productData.warehouse_name || 'N/A',
                tax_percentage: taxPercentage,
                tax_amount: taxAmount.toFixed(2),
                total_amount_inc_tax: totalAmountIncTax.toFixed(2),
                exist: exist
            };

            items.push(item);
            renderTable();
            updateSummary();
        }

        function addProductToTable2(productData, exist = 0) {
            const unitPrice = parseFloat(productData.price) || productData.unit_price || 0;
            const quantity = productData.qty;
            const discountPercentage = parseFloat(productData.discount_percentage) || parseFloat(productData.gstprice) || 0;

            const discount = (quantity * unitPrice) * (discountPercentage / 100);
            const lineTotal = (unitPrice * quantity) - discount || productData.linetotal;
            const id = productData.id || productData.product_id || null;

            const taxPercentage = parseFloat(productData.tax) || parseFloat(productData.taxpercentage) || 0;
            const taxAmount = lineTotal * (taxPercentage / 100);
            const totalAmountIncTax = lineTotal + taxAmount;

            const item = {
                id: id,
                productName: productData.product_name,
                description: productData.type || productData.description || '',
                unitPrice,
                quantity,
                uom: productData.uom,
                uom_name: productData.uom_name || '',
                lineTotal,
                location: productData.warehouse_name,
                item_type: productData.item_type || '',
                discount_percentage: discountPercentage,
                warranty: productData.warrenty || 'N/A',
                warehouse_name: productData.warehouse_name || 'N/A',
                tax_percentage: taxPercentage,
                tax_amount: taxAmount.toFixed(2),
                total_amount_inc_tax: totalAmountIncTax.toFixed(2),
                exist: exist
            };

            items.push(item);
            renderTable2();
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

            <td class="unit-price-cell" data-index="${index}">${item.unitPrice}</td>
            <td class="qty-cell" data-index="${index}">${item.quantity}</td>
               <td class="qty-cell" data-index="${index}">${item.uom_name}</td>
            <td class="discount-percentage-cell" data-index="${index}">${item.discount_percentage ? `${item.discount_percentage}%` : 'N/A'}</td>
            <td class="line-total-cell" data-index="${index}">${item.lineTotal}</td>
            <td class="warranty-cell" data-index="${index}">${item.warranty}</td>
         
            <td>
                <input type="number" class="tax-percentage-cell" data-index="${index}" value="${item.tax_percentage}" min="0" max="100">
            </td>
            <td class="tax-amount-cell" data-index="${index}">${item.tax_amount}</td>
            <td class="total-amount-cell" data-index="${index}">${item.total_amount_inc_tax}</td>
            <input type="hidden" class="product-id" value="${item.id}" />
             <input type="hidden" class="product-exist" value="${item.exist}" />
               <input type="hidden" class="location" value="${item.location}" />
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
                        items[index].description = firstProduct.type || '';
                        items[index].item_no = firstProduct.item_no || '';
                        items[index].warranty = firstProduct.warranty;
                        items[index].uom_name = firstProduct.uom_name || '';
                        items[index].uom = firstProduct.uom || '';
                        items[index].location = firstProduct.warehouse_name;
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
                    const currentItemNo = items[index].item_no;
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
                        items[index].item_no = product.item_no;
                        items[index].warranty = product.warranty;
                        items[index].uom = product.uom;
                        items[index].uom_name = product.uom_name;
                        items[index].location = product.warehouse_name;
                        items[index].description = product.type || '';
                        items[index].unitPrice = parseFloat(product.price) || 0;
                        items[index].item_type = product.item_type || 'standard';
                        items[index].discount_percentage = parseFloat(product.discount_percentage) || 0;

                        const discount = (items[index].quantity * items[index].unitPrice) * (items[
                                index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * items[index].quantity) -
                            discount;

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

            document.querySelectorAll('.item_no-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const currentDescription = items[index].item_no;

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentDescription;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newDescription = this.value.trim();
                        items[index].item_no = newDescription;
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function collectFormData() {
                let formData = {
                    workorder_id: $('#workorder_id').val(),
                    products: collectProductDetails(),

                };

                return formData;
            }

            function collectProductDetails() {
                let products = [];

                $('#productTableBody tr').each(function() {
                    let product = {
                        product_id: $(this).find('.product-id')
                            .val(), // Retrieve the hidden product ID
                        warehouse_id: $(this).find('.location')
                            .val(),
                        uom: $(this).find('.uom')
                            .val(),
                        product_exist: $(this).find('.product-exist')
                            .val(), // Retrieve the hidden product exist
                        item_no: $(this).find('td:eq(2)').text(),
                        product_name: $(this).find('td:eq(3)').text(),

                        unit_price: $(this).find('td:eq(4)').text(),
                        quantity: $(this).find('td:eq(5)').text(),
                        uom_name: $(this).find('td:eq(6)').text(),
                        gst: $(this).find('td:eq(7)').text(),
                        line_total: $(this).find('td:eq(8)').text(),
                        warranty: $(this).find('td:eq(9)').text(),
                        location: $(this).find('td:eq(10)').text(),
                        taxpercentage: $(this).find('.tax-percentage-cell').val(),
                        taxamount: $(this).find('.tax-amount-cell').text(),
                        totalamount: $(this).find('.total-amount-cell').text(),

                    };
                    products.push(product);
                });

                return products;
            }

            $('#startBooking').click(function(e) {
                e.preventDefault();

                let formData = collectFormData();




                $.ajax({
                    url: "{{ route('quotation.update') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Quotation updated successfully');

                    },
                    error: function(error) {
                        alert('Error starting booking');
                        console.error(error);
                    }
                });
            });
        });
    </script>

    <script>
        function toggleFaq(index) {
            const faqAnswer = document.getElementById(`faq${index}`);
            const faqQuestion = faqAnswer.previousElementSibling;
            faqAnswer.classList.toggle('show');
            faqQuestion.classList.toggle('active');
        }

        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const bookingModal = document.getElementById("bookingModal");
            const closeBookingModalButton = document.getElementById(
                "closeBookingModal");


            $(document).on('click', '.book-button', function() {
                const technicianName = $(this).closest('tr').find(
                    'td:first').text();
                const technicianId = $(this).data('technician-id');


                $('#technicianName').val(technicianName);
                $('#technicianId').val(technicianId);

                bookingModal.style.display = "block";
            });

            closeBookingModalButton.addEventListener("click", function() {
                bookingModal.style.display = "none";
            });

            window.addEventListener("click", function(event) {
                if (event.target === bookingModal) {
                    bookingModal.style.display = "none";
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#customer_id').select2({
                placeholder: 'Select your customer',
                allowClear: true
            });
            $('#searchPayment').select2({
                placeholder: 'Select your payment',
                allowClear: true
            });
            $('#searchInvoice').select2({
                placeholder: 'Select your invoice',
                allowClear: true
            });
            $('#searchVehicle').select2({
                placeholder: 'Select your vehicle',
                allowClear: true
            });
            $('#searchTechnician').select2({
                placeholder: 'Select your technician',
                allowClear: true
            });
            $('#searchInspection').select2({
                placeholder: 'Select your inspection',
                allowClear: true
            });
            $('#searchBooking').select2({
                placeholder: 'Select your booking',
                allowClear: true
            });

            var selectedCustomerId = $('#customer_id').val();
            if (selectedCustomerId) {
                fetchClientData(selectedCustomerId);
            }
            $('#customer_id').on('change', function() {
                var customerId = this.value;
                fetchClientData(customerId);
            });

            function fetchClientData(customerId) {
                if (customerId) {
                    fetch(`/workorder-client/fetch/api/${customerId}`)
                        .then(response => response.json())
                        .then(data => {
                            $('#customer_name').val(data.full_name);
                            $('#phone').val(data.phone);
                            $('#email').val(data.email);
                            $('#type').val(data.type);
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    $('#customer_name').val('');
                    $('#phone').val('');
                    $('#email').val('');
                    $('#type').val('');
                }
            }

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
                            var suggestions = $(
                                '#addressSuggestions');
                            suggestions.empty();
                            if (data.candidates && data
                                .candidates.length > 0) {
                                suggestions.show();
                                data.candidates.forEach(
                                    function(place) {
                                        var listItem =
                                            $(
                                                '<li class="list-group-item"></li>')
                                            .text(place
                                                .formatted_address
                                            )
                                            .data(
                                                'address',
                                                place
                                                .formatted_address
                                            )
                                            .on('click',
                                                function() {
                                                    $('#service_location')
                                                        .val(
                                                            $(
                                                                this)
                                                            .data(
                                                                'address'
                                                            )
                                                        );
                                                    suggestions
                                                        .hide();
                                                });
                                        suggestions
                                            .append(
                                                listItem
                                            );
                                    });
                            } else {
                                suggestions.hide();
                            }
                        },
                        error: function() {
                            alert(
                                "Error fetching address.");
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
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("availabilityModal");
            const openModalButton = document.getElementById("openModalButton");
            const closeModalButton = document.getElementById(
                "closeModalButton");

            // Show modal on button click
            openModalButton.addEventListener("click", function() {
                modal.style.display = "block";
                // Load initial data
                updateTechnicianAvailability($('#currentDate').val());
            });

            // Close modal on button click
            closeModalButton.addEventListener("click", function() {
                modal.style.display = "none";
            });

            // Close modal when clicking outside of it
            window.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });




            // Update the day of the week based on the selected date
            function updateDayOfWeek(date) {
                const dayOfWeek = new Date(date).toLocaleString('default', {
                    weekday: 'long'
                });
                $('#currentDay').text(dayOfWeek);
            }

            // Event listener for previous date button
            $('#prevDateBtn').on('click', function() {
                let currentDate = new Date($('#currentDate').val());
                currentDate.setDate(currentDate.getDate() - 1);
                $('#currentDate').val(currentDate.toISOString().split(
                    'T')[0]); // Set the new date value
                updateTechnicianAvailability($('#currentDate')
                    .val()); // Update availability
            });

            // Event listener for next date button
            $('#nextDateBtn').on('click', function() {
                let currentDate = new Date($('#currentDate').val());
                currentDate.setDate(currentDate.getDate() + 1);
                $('#currentDate').val(currentDate.toISOString().split(
                    'T')[0]); // Set the new date value
                updateTechnicianAvailability($('#currentDate')
                    .val()); // Update availability
            });

            // Initialize time bars based on availability
            function initializeTimeBars() {
                const timeBars = document.querySelectorAll(
                    '.time-bar-manipulatable');
                timeBars.forEach(bar => {
                    const startTime = bar.getAttribute('data-start');
                    const endTime = bar.getAttribute('data-end');

                    const startHour = parseInt(startTime.split(':')[0],
                        10);
                    const startMinute = parseInt(startTime.split(':')[
                        1], 10);
                    const endHour = parseInt(endTime.split(':')[0], 10);
                    const endMinute = parseInt(endTime.split(':')[1],
                        10);

                    const startInMinutes = (startHour * 60) +
                        startMinute;
                    const endInMinutes = (endHour * 60) + endMinute;

                    const totalWorkingMinutes = endInMinutes -
                        startInMinutes;
                    const totalHoursRange = (endHour - startHour) * 60;
                    const barWidth = (totalWorkingMinutes /
                        totalHoursRange) * 100;

                    const startPercentage = ((startInMinutes -
                        startHour * 60) / totalHoursRange) * 100;
                    bar.style.left = `${startPercentage}%`;
                    bar.style.width = `${barWidth}%`;
                });
            }

            // Initialize time bars on first load
            initializeTimeBars();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fromDateInput = document.getElementById("fromDate");
            const toDateInput = document.getElementById("toDate");
            const fromTimeInput = document.getElementById("fromTime");
            const toTimeInput = document.getElementById("toTime");

            const today = new Date().toISOString().split("T")[0];
            fromDateInput.value = today;
            toDateInput.value = today;

            function setTimeFields() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                fromTimeInput.value = `${hours}:${minutes}`;

                const toTime = new Date(now.getTime() + 30 * 60000);
                const toHours = String(toTime.getHours()).padStart(2, '0');
                const toMinutes = String(toTime.getMinutes()).padStart(2, '0');
                toTimeInput.value = `${toHours}:${toMinutes}`;
            }

            setTimeFields();

            fromDateInput.addEventListener("change", function() {
                toDateInput.min = this.value;
                if (toDateInput.value < this.value) {
                    toDateInput.value = this.value;
                }
            });

            toDateInput.addEventListener("change", function() {
                if (this.value < fromDateInput.value) {
                    this.value = fromDateInput.value;
                }
            });

            fromTimeInput.addEventListener("change", function() {
                const fromTime = new Date(
                    `1970-01-01T${this.value}:00`);
                const minToTime = new Date(fromTime.getTime() + 30 *
                    60000);
                const minToTimeStr = minToTime.toISOString().substr(11,
                    5);
                toTimeInput.min = minToTimeStr;

                if (toTimeInput.value < minToTimeStr) {
                    toTimeInput.value = minToTimeStr;
                }
            });

            toTimeInput.addEventListener("change", function() {
                const fromTime = new Date(
                    `1970-01-01T${fromTimeInput.value}:00`);
                const minToTime = new Date(fromTime.getTime() + 30 *
                    60000);
                const minToTimeStr = minToTime.toISOString().substr(11,
                    5);
                if (this.value < minToTimeStr) {
                    this.value = minToTimeStr;
                }
            });
        });
    </script>

    <script>
        $(document).on("click", ".customModal", function() {

            $(".modaldemo1").modal("hide");


            setTimeout(() => {
                $("#yourModalId").modal("show");
            }, 500);
        });

        $(document).on("shown.bs.modal", function() {
            const today = new Date();

            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = (date.getMonth() + 1).toString().padStart(2, "0");
                const day = date.getDate().toString().padStart(2, "0");
                return `${year}-${month}-${day}`;
            };

            $("#fromDate").val(formatDate(today));

            const now = new Date();

            $("#fromTime").val(now.toTimeString().substr(0, 5));

            function updateToTime() {
                let fromTime = $("#fromTime").val();
                if (fromTime) {
                    let [hours, minutes] = fromTime.split(":").map(Number);
                    minutes += 30;
                    if (minutes >= 60) {
                        hours += 1;
                        minutes -= 60;
                    }
                    const toTime = `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
                    $("#toTime").val(toTime);
                }
            }

            // When the fromTime changes, update toTime
            $("#fromTime").on("input", updateToTime);

            // Call the function initially to set the toTime
            updateToTime();
        });
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
                            <div class="d-flex align-items-start">
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

                        }
                    });
                }
            });
        });
    </script>
    @push('script-page')
    @endpush
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/accordion.js') }}"></script>
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

    @include('workorder.workOrderMap')
@endsection
