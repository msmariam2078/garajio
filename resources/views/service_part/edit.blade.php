<div class="modal-body ms-5">
    {{ Form::model($servicePart, array('route' => array('services-parts.update', $servicePart->id), 'method' => 'PUT')) }}

    <div class="row">

        <div class="form-group col-md-6">
            <label for="item_number" class="form-label">    Product ID</label>
            <input type="text" class="form-control" name="item_number" id="item_number"
                value="{{$servicePart->item_no}}" placeholder="Enter Item Number">
        </div>


        <div class="form-group col-md-6">
            <label for="description" class="form-label">Product Name</label>
            <input type="text" class="form-control" name="description" id="description"
                value="{{$servicePart->product_name}}" placeholder="Enter Description" require>
        </div>


        <div class="form-group col-md-6">
            <label for="category" class="form-label">Category</label>
            <select class="form-control" name="category" id="category">
                @foreach($category as $id => $name)
                <option value="{{ $id }}" {{$id==$servicePart->category? 'selected ': ''}}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="warehouse" class="form-label">Warehouse</label>
            <select class="form-control" name="warehouse" id="warehouse">
                @foreach($warehouse as $id=>$item)
                <option value="{{ $id }}" {{$id==$servicePart->warehouse_id? 'selected ': ''}}>{{ $item}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="item_type" class="form-label">Item Type</label>
            <select class="form-control" name="item_type" id="item_type">
                <option value="Inventory" {{$servicePart->item_type=='Inventory'? 'selected': ''}}>Inventory</option>
                <option value="Non-inventory" {{$servicePart->item_type=='Inventory'? 'selected': ''}}>Non Inventory</option>
                <option value="Service" {{$servicePart->item_type=='Service'? 'selected': ''}}>Service</option>
               <option value="Scrap" {{$servicePart->item_type=='Scrap'? 'selected': ''}}>Scrap</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="qty_on_hand" class="form-label">Quantity</label>
            <input type="text" class="form-control" name="qty_on_hand" id="qty_on_hand" placeholder="Enter Quantity"
                value="{{$servicePart->qty_on_hand}}" readonly>
        </div>

        <div class="form-group col-md-6">
            <label for="unit" class="form-label">UOM</label>
            <select class="form-control" name="unit" id="unit">
                @foreach($uom as $item)
                <option value="{{ $item->id }}" {{ $item->id == $servicePart->uom ? 'selected' : '' }}>
                    {{ $item->title }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="retail_price" class="form-label">Sales Price</label>
            <input type="number" class="form-control" name="sales_price" id="retail_price"
                value="{{$servicePart->sales_price}}" placeholder="Enter Sales Price" step="0.01" min="0">
        </div>

        <div class="form-group col-md-6">
            <label for="price" class="form-label">Purchase Price</label>
            <input type="number" class="form-control" name="price" id="price" placeholder="Enter Purchase Price"
                value="{{$servicePart->price}}" step="0.01" min="0">
        </div>

        <div class="form-group col-md-6">
            <label for="tax" class="form-label">Tax</label>
            <input type="number" class="form-control" name="tax" id="tax" placeholder="Enter Tax" step="0.01"
                value="{{$servicePart->tax}}" min="0">
        </div>

        <div class="form-group col-md-6">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" name="image" id="image">
        </div>

        <div class="form-group col-md-6">
            <label for="warranty" class="form-label">Warranty(In Months)</label>
            <input type="text" class="form-control" name="warranty" id="warranty" value="{{$servicePart->warranty}}"
                placeholder="Enter Warranty Period">
        </div>
        <div class="form-group col-md-6">
            <label for="brand" class="form-label">Brand</label>
            <select class="form-control" name="brand" id="brand">
                @foreach($brand as $item)
                <option value="{{ $item->id }}" {{ $item->id == $servicePart->brand ? 'selected' : '' }}>
                    {{ $item->description }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="origin" class="form-label">Origin</label>
            <select class="form-control" name="origin" id="origin">
                @foreach($origin as $item)
                <option value="{{ $item->id }}" {{ $item->id == $servicePart->origin ? 'selected' : '' }}>
                    {{ $item->description }}
                </option>
                @endforeach
            </select>
        </div>

		 <div class="form-group col-md-6 mb-3">
                <label for="reference_type" class="form-label">{{ __('Reference type') }}</label>
                <select name="reference_type" id="reference_type" class="form-control ">
                    <option value="barcode" {{ $servicePart->reference_type == 'barcode' ? 'selected' : '' }}>Barcode</option>
                </select>
            </div>

			  <div class="form-group col-md-6">
                <label for="reference_number" class="form-label">Reference number</label>
                <input type="text" class="form-control" value=" {{ $servicePart->reference_number }}" name="reference_number" id="reference_number" placeholder="Enter reference number">
            </div>



        <div class="form-group col-md-6">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
    </form>
</div>