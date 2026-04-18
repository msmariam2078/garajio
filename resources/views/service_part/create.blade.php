<div class="modal-body">

    <div class="row d-flex justify-content-center mb-4">
        <div class="form-group d-flex justify-content-center">
            <div class="form-check client_type">
                <label id="type_standard" class="form-check-label individual active_customer_type"
                    onclick="toggleFields('standard')">
                    Standard
                </label>


            </div>
            <div class="form-check client_type">
                <label id="type_bundle" class="form-check-label corporate" onclick="toggleFields('bundle')">
                    Bundle
                </label>


            </div>
          </div>
    </div>
  
    <div id="standard_fields">

    <form action="{{ url('services-parts') }}" method="post" enctype="multipart/form-data">
    @csrf
         <div class="row">
            <div class="form-group col-md-6">
                <label for="item_number" class="form-label">Product ID</label>
                <input type="text" class="form-control" name="item_number" id="item_number" value="{{$setup->auto ? $setup->item_prefix.$setup->item_number : ''}}"
                    placeholder="Enter Item Number">
            </div>

            <div class="form-group col-md-6">
                <label for="description" class="form-label">Product Name</label>
                <input type="text" class="form-control" name="description" id="description"
                    placeholder="Enter Description">
            </div>

            
            <div class="form-group col-md-6">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" name="category" id="category">
                    @foreach($category as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="warehouse" class="form-label">Ware House</label>
                <select class="form-control" name="warehouse" id="warehouse" >
                    @foreach($warehouse as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="item_type" class="form-label">Item Type</label>
                <select class="form-control" name="item_type" id="item_type">
                    <option value="Inventory">Inventory</option>
                    <option value="Non-inventory">Non Inventory</option>
                    <option value="Service">Service</option>
                    <option value="Scrap">Scrap</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="qty_on_hand" class="form-label">Quantity</label>
                <input type="text" class="form-control" name="qty_on_hand" id="qty_on_hand" placeholder="Enter Quantity"
                    readonly>
            </div>

            <div class="form-group col-md-6">
                <label for="unit" class="form-label">UOM</label>
                <select class="form-control" name="unit" id="unit">
                    @foreach($uom as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="sales_price" class="form-label">Sales Price</label>
                <input type="number" class="form-control" name="sales_price" id="retail_price"
                    placeholder="Enter Sales Price" step="0.01" min="0">
            </div>

            <div class="form-group col-md-6">
                <label for="price" class="form-label">Purchase Price</label>
                <input type="number" class="form-control" name="price" id="price" placeholder="Enter Purchase Price"
                    step="0.01" min="0">
            </div>

            <div class="form-group col-md-6">
                <label for="tax" class="form-label">Tax</label>
                <input type="number" class="form-control" name="tax" id="tax" placeholder="Enter Tax" step="0.01"
                    min="0">
            </div>

            <div class="form-group col-md-6">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" name="image" id="image">
            </div>

            <div class="form-group col-md-6">
                <label for="warranty" class="form-label">Warranty(In Months)</label>
                <input type="text" class="form-control" name="warranty" id="warranty"
                    placeholder="Enter Warranty Period">
            </div>

            <div class="form-group col-md-6 mb-3">
                <label for="brand" class="form-label">{{ __('Brand') }}</label>
                <select name="brand" id="brand" class="form-control ">
                    <option value="">Select Brand</option>
                    @foreach ($brand as $item)
                    <option value="{{ $item->id }}">{{ $item->description }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label for="origin" class="form-label">{{ __('Origin') }}</label>
                <select name="origin" id="origin" class="form-control ">
                    <option value="">Select Origin</option>
                    @foreach ($origin as $item)
                    <option value="{{ $item->id }}">{{ $item->description }}</option>
                    @endforeach
                </select>
            </div>

			 <div class="form-group col-md-6 mb-3">
                <label for="reference_type" class="form-label">{{ __('Reference type') }}</label>
                <select name="reference_type" id="reference_type" class="form-control ">
                    <option value="barcode">Barcode</option>
                </select>
            </div>

			  <div class="form-group col-md-6">
                <label for="reference_number" class="form-label">Reference number</label>
                <input type="text" class="form-control" name="reference_number" id="reference_number" placeholder="Enter reference number">
            </div>

            <div class="form-group col-md-6">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </div>
           </form>

    </div>

    <div id="bundle_fields" class="d-none">
    <form action="{{ url('services-parts') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Description Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" id="description" class="form-control"
                placeholder="{{ __('Enter description') }}" require>
        </div>

        <!-- Item Group Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="item_group" class="form-label">{{ __('Item Group') }}</label>
            <select name="item_group" id="item_group" class="form-control ">
                @foreach ($origin as $key => $value)
                <option value="{{ $value->id }}">{{ $value->description }}</option>
                @endforeach
            </select>
        </div>

        <!-- Item Type Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="item_type" class="form-label">{{ __('Item Type') }}</label>
            <select name="item_type" id="item_type" class="form-control">
                <option value="inventory">{{ __('Inventory') }}</option>
                <option value="services">{{ __('Services') }}</option>
                <option value="noninventory">{{ __('Non Inventory') }}</option>
            </select>
        </div>

        <!-- Category Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="category" class="form-label">{{ __('Category') }}</label>
            <select name="category" id="category" class="form-control ">
                @foreach ($category as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <!-- Warehouse Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="warehouse" class="form-label">{{ __('Warehouse') }}</label>
            <select name="warehouse" id="warehouse" class="form-control ">
                @foreach ($warehouse->pluck('name', 'id') as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Quantity Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="qty_on_hand" class="form-label">{{ __('Quantity') }}</label>
            <input type="text" name="qty_on_hand" id="qty_on_hand" class="form-control"
                placeholder="{{ __('Enter Quantity') }}">
        </div>

        <!-- UOM Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="unit" class="form-label">{{ __('UOM') }}</label>
            <select name="unit" id="unit" class="form-control ">
                @foreach ($uom as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <!-- Sales Price Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="retail_price" class="form-label">{{ __('Sales Price') }}</label>
            <input type="number" name="retail_price" id="retail_price" class="form-control"
                placeholder="{{ __('Enter Sales Price') }}" step="0.01" min="0">
        </div>

        <!-- Purchase Price Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="price" class="form-label">{{ __('Purchase Price') }}</label>
            <input type="number" name="price" id="price" class="form-control"
                placeholder="{{ __('Enter Purchase price') }}" step="0.01" min="0">
        </div>

        <!-- Tax Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="tax" class="form-label">{{ __('Tax') }}</label>
            <input type="number" name="tax" id="tax" class="form-control" placeholder="{{ __('Enter tax') }}"
                step="0.01" min="0">
        </div>

        <!-- Image Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="image" class="form-label">{{ __('Image') }}</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <!-- Warranty Field -->
        <div class="form-group col-md-6 mb-3">
            <label for="warranty" class="form-label">{{ __('Warranty') }}</label>
            <input type="text" name="warranty" id="warranty" class="form-control"
                placeholder="{{ __('Enter warranty') }}">
        </div>

        <div class="form-group col-md-6 mb-3">
            <label for="brand" class="form-label">{{ __('Brand') }}</label>
            <select name="brand" id="brand" class="form-control ">
                <option value="">Select Brand</option>
                @foreach ($brand as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6 mb-3">
            <label for="origin" class="form-label">{{ __('Origin') }}</label>
            <select name="origin" id="origin" class="form-control ">
                <option value="">Select Origin</option>
                @foreach ($origin as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group col-md-6">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

    </div>
    </div>
   
        </form>
</div>

<script>
// Function to toggle between standard and bundle fields
function toggleFields(type) {
    // Get the forms for standard and bundle
    var standardFields = document.getElementById('standard_fields');
    var bundleFields = document.getElementById('bundle_fields');

    // Check the selected type and toggle the visibility
    if (type === 'standard') {
        standardFields.classList.remove('d-none');
        bundleFields.classList.add('d-none');
        
        document.getElementById('type_standard').classList.add('active_customer_type');
        document.getElementById('type_bundle').classList.remove('active_customer_type');
    } else if (type === 'bundle') {
        standardFields.classList.add('d-none');
        bundleFields.classList.remove('d-none');
        
        document.getElementById('type_standard').classList.remove('active_customer_type');
        document.getElementById('type_bundle').classList.add('active_customer_type');
    }
}

// Add event
</script>