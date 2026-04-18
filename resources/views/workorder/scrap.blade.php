<!-- row opened -->
<div class="modal-body" id="customModal">
    <div class="rcustom-control custom-switch mb-3">
        <input type="checkbox" class="custom-control-input" id="customSwitch1">
        <label class="custom-control-label"style="width: 250px !important;" for="customSwitch1">
            Would you collected the scrap or not?</label>
    </div>
    <br>
    @if ($scrap?->scrap_module == 1)
        <input type="hidden" class="" value="{{ $workorder }}" id="workorder">
        <input type="hidden" class="" value="{{ $source }}" id="source">
        <div class="table-responsive d-none mb-5" id='tableitem'>
            <table class="table text-nowrap table-bordered" style="width: 400px !important;">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody2"></tbody>
            </table>
            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="addProduct2">Add
                Scrap</button>
        </div>
        <div class="form-group my-4">
            <div class="custom-control custom-checkbox d-flex align-items-start">
                <input type="checkbox" class="custom-control-input mt-1" id="term" name="term">
                <label class="custom-control-label ml-2 font-weight-bold text-justify" for="term">
                    I hereby confirm receipt of the goods in good condition and agree to dispose of the old battery in
                    compliance with UAE regulations through authorized channels, ensuring proper handling and recycling.
                </label>
            </div>
        </div>
    @endif

    <button type="button" class="btn btn-secondary mapmodal_close ml-2 float-right" id="close">Cancel</button>
    <button class="btn btn-primary float-right" id="save">Confirm & submit</button>
</div>

<script>
    $('#close').click(function() {
        $("#customModal2").modal("hide");
    });
    $(document).ready(function() {
        var items = [];
        document.getElementById('customSwitch1').addEventListener('click', async function() {
            if (this.checked) {
                document.getElementById('tableitem').classList.remove("d-none");
            } else {
                document.getElementById('tableitem').classList.add("d-none");
            }
        });

        document.getElementById('addProduct2').addEventListener('click', async function() {
            console.log(1);
       
            const firstProduct = await fetchFirstProduct2(2, 1);

            if (firstProduct) {
                addProductToTable2(firstProduct);
            }
            this.classList.add("d-none");
        });
        async function fetchSimilarProducts(query) {

            try {
                const response = await fetch(
                    `/searchproducts2?search=${query}`
                );
                const products = await response.json();
                return products;
            } catch (error) {
                console.error('Error fetching similar products:', error);
                return [];
            }
        }
        
        async function fetchFirstProduct2(no, vehicleId) {

            try {
                const response = await fetch(
                    `/searchproducts${no}`
                );
                const products = await response.json();
                console.log(products);
                return products.length > 0 ? products[0] : null;
            } catch (error) {
                console.error('Error fetching the first product:', error);
                return null;
            }
        }

        function addProductToTable2(productData, exist = 0) {


            const item = {
                id: productData.id,
                productName: productData.product_name,
                item_type: productData.item_type || '',
                qty: productData.qty || 1
            };

            items.push(item);
            console.log(items);
            renderTable();

        }

        function renderTable() {
            const tableBody = document.getElementById('productTableBody2');
            tableBody.innerHTML = '';

            items.forEach((item, index) => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
					<td>${item.item_type}</td>
           
           <td class="product-name-cell" data-index="${index}">${item.productName}</td>
            <td class="qty-cell" data-index="${index}">${item.qty}</td>
			<td>
                <button class="btn btn-danger btn-sm delete-row" data-index="${index}">Delete</button>
            </td>
            <input type="hidden" class="product-id" value="${item.id}" />
        
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
               let addbutton = document.getElementById('addProduct2');
               addbutton.classList.remove("d-none");
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


                    // Fetch the first product of the selected item type
                    const firstProduct = await fetchFirstProductByType('scrap');

                    if (firstProduct) {
                        items[index].productName = firstProduct.product_name;
                        items[index].item_type = firstProduct.type || '';


                        items[index].item_type = newItemType;

                        // Recalculate the line total
                        const quantity = items[index].qty;

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

                        const similarProducts = await fetchSimilarProducts(
                            query);
                        dropdown.innerHTML = '';

                        // **Create table header**
                        const header = document.createElement('div');
                        header.style.display = 'flex';
                        header.style.fontWeight = 'bold';
                        header.style.padding = '8px';
                        header.style.backgroundColor = '#f8f9fa';
                        header.innerHTML = `<div style="width: 30%; padding-right: 10px;">SN</div>
                                <div style="width: 70%;">Description</div>`;
                        dropdown.appendChild(header);

                        similarProducts.forEach((product, i) => {
                            const option = document.createElement(
                                'div');
                            option.classList.add('dropdown-option');
                            option.style.display = 'flex';
                            option.style.padding = '8px';
                            option.style.cursor = 'pointer';
                            option.style.borderBottom =
                                '1px solid #eee';
                            option.innerHTML = `<div style="width: 30%; padding-right: 10px;">${product.id}</div>
                                    <div style="width: 70%;">${product.product_name}</div>`;

                            option.addEventListener('mouseenter',
                                () => {
                                    document.querySelectorAll(
                                            '.dropdown-option')
                                        .forEach(opt => {
                                            opt.style
                                                .backgroundColor =
                                                '#fff';
                                            opt.style.color =
                                                '#000';
                                        });
                                    option.style.backgroundColor =
                                        '#007bff';
                                    option.style.color = '#fff';
                                    selectedIndex = i;
                                });

                            option.addEventListener('click',
                                function() {
                                    updateSelectedProduct(index,
                                        product);
                                });

                            dropdown.appendChild(option);
                        });
                    });

                    input.addEventListener('keydown', function(event) {
                        const options = dropdown.querySelectorAll(
                            '.dropdown-option');
                        if (event.key === 'ArrowDown') {
                            selectedIndex = (selectedIndex + 1) % options.length;
                            highlightOption(options, selectedIndex);
                        } else if (event.key === 'ArrowUp') {
                            selectedIndex = (selectedIndex - 1 + options.length) %
                                options
                                .length;
                            highlightOption(options, selectedIndex);
                        } else if (event.key === 'Enter' && selectedIndex >= 0) {
                            event.preventDefault();
                            updateSelectedProduct(index, similarProducts[
                                selectedIndex]);
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
                        items[index].description = product.type || '';
                        items[index].unitPrice = parseFloat(product.price) || 0;
                        items[index].item_type = product.item_type || 'standard';
                        items[index].discount_percentage = parseFloat(product
                            .discount_percentage) || 0;

                        const discount = (items[index].quantity * items[index].unitPrice) *
                            (items[
                                    index]
                                .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice * items[index]
                                .quantity) -
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


                        const discount = (items[index].quantity * newUnitPrice) * (
                            items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (newUnitPrice * items[index]
                            .quantity) - discount;


                        const taxAmount = items[index].lineTotal * (items[index]
                            .tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(2);
                        items[index].total_amount_inc_tax = (items[index].lineTotal +
                                taxAmount)
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
                    const currentQuantity = items[index].qty;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.min = '1';
                    input.value = currentQuantity;

                    this.innerHTML = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        const newQuantity = parseInt(this.value) || 1;
                        items[index].qty = newQuantity;

                        // **Recalculate Line Total**
                        const discount = (newQuantity * items[index].unitPrice) * (
                            items[index]
                            .discount_percentage / 100);
                        items[index].lineTotal = (items[index].unitPrice *
                            newQuantity) - discount;

                        // **Recalculate Tax Amount**
                        const taxAmount = items[index].lineTotal * (items[index]
                            .tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(2);

                        // **Recalculate Total Amount (Including Tax)**
                        items[index].total_amount_inc_tax = (items[index].lineTotal +
                                taxAmount)
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
                        const discount = (items[index].quantity * items[index]
                            .unitPrice) * (
                            newDiscount / 100);
                        items[index].lineTotal = (items[index].unitPrice * items[index]
                                .quantity) -
                            discount;

                        // **Recalculate Tax Amount**
                        const taxAmount = items[index].lineTotal * (items[index]
                            .tax_percentage /
                            100);
                        items[index].tax_amount = taxAmount.toFixed(2);

                        // **Recalculate Total Amount (Including Tax)**
                        items[index].total_amount_inc_tax = (items[index].lineTotal +
                                taxAmount)
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
                        items[index].total_amount_inc_tax = (newLineTotal + taxAmount)
                            .toFixed(2);

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
    });
</script>
<script>
    $(document).ready(function() {
        function collectFormData() {
            let formData = {
                workorder_id: $('#workorder').val(),
                source: $('#source').val(),
                products: collectProductDetails(),

            };

            return formData;
        }

        function collectProductDetails() {
            let products = [];

            $('#productTableBody2 tr').each(function() {
                let product = {
                    product_id: $(this).find('.product-id')
                        .val(), // Retrieve the hidden product ID

                    product_name: $(this).find('product-name-cell').text(),
                    qty: $(this).find('.qty-cell').text(),

                };
                products.push(product);
            });

            return products;
        }

        $('#save').click(function(e) {
            e.preventDefault();
            let formData = collectFormData();

            if (collectFormData().products.length > 0) {
                $.ajax({
                    url: "{{ route('workorder.scrap') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.source == 'admin') {
                            alert(response.message);
                            window.location.href = '/workorder/' + response.workorder_id +
                                '/edit';

                        } else {
                            window.location.href = '/workorder/details/' + response
                                .workorder_id;
                        }
                    },
                    error: function(error) {
                        alert('Error saving ');
                        console.error(error);
                    }
                });
            } else {
                alert('there are not item to saving ');
            }

        });
    });
</script>
