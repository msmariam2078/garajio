<style> 
      .custom { 
        width: 800px; 
      min-height:400px ;
        padding: 20px;
      } 
    </style>
  

<div class="modal fade" id="update_invoice{{$invoice->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content p-5 custom">
          <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Invoice</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body ms-5">
            
          <form method="POST" action="{{ route('invoice.update',$invoice->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row">


<div class="form-group col-md-12 mb-4 ">
    <label for="requested_time" class="form-label mb-3 mb-3">Invoice Date</label>
    <input type="date" class="form-control" name="invoice_date" id="invoice_date"
        placeholder="Enter Requested Date" value="{{ old('requested_time', $invoice->invoice_date) }}">
</div>
<div class="form-group col-md-6 mb-4">
    <label for="client" class="form-label mb-3 mb-3">{{ __('Client') }}</label>
    <select id="client" name="client" class="form-control hidesearch" onchange="getVehicle(this.value)"
        required>
        <option value="">Select Client</option>
        @foreach ($clients as $key => $value)
        <option value="{{ $value->id }}" {{( $value->id== $invoice->client )? 'selected' : '' }}>{{ $value->first_name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-6 mb-4 ">
    <label for="workorder" class="form-label mb-3 mb-3">Work Order</label> 
    <div class="workorder_div">
        <select class="form-control" id="workorder" name="workorder">
            <option value="1" data-select2-id="2">Dummy1</option>
            <option value="2" data-select2-id="7">Dummy2</option>
        </select>
    </div>
</div>

<div class="form-group col-md-4 mb-4">
    <label for="total" class="form-label mb-3 mb-3">Total Amount</label> 
    <input type="number" name="total" id="total" class="form-control" placeholder="Enter total amount"
        value="{{ old('total', $invoice->total) }}" required>
</div>
<div class="form-group col-md-4 mb-4">
    <label for="discount" class="form-label mb-3 mb-3">Discount Amount</label>
    <input type="number" name="discount" id="discount" class="form-control" placeholder="Enter discount"
        value="{{ old('discount', $invoice->discount) }}">
</div>
<div class=" form-group col-md-4 mb-4">
    <label for="final_amount" class="form-label mb-3 mb-3">Final Amount</label>
    <input type="number" name="final_amount" id="final_amount" class="form-control"
        placeholder="Final amount" value="{{ old('final_amount', $invoice->final_amount) }}" readonly>
</div>
<div class="form-group col-md-6 mb-4">
    <label for="requested_time" class="form-label mb-3 mb-3">Due Date</label>
    <input type="date" class="form-control" name="due_date" id="due_date" placeholder="Enter Requested Date"
        value="{{ old('due_date', $invoice->due_date) }}">
</div>


<div class=" form-group col-md-6 mb-4">
    <label for="status" class="form-label mb-3 mb-3">Status</label>
    <select class="form-control" name="status">
        <option value="">Select Status</option>
        <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>unpaid
        </option>
        <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>paid</option>
    </select>
</div>
<div class="form-group col-md-12 mb-4">
    <label for="service_location" class="form-label mb-3 mb-3">Note</label>
    <textarea class="form-control" name="notes" id="notes"
        rows="3">{{ old('notes', $invoice->notes) }}</textarea>
</div>
</div>
          </div>
          <div class="modal-footer">
            <input class="btn btn-primary btn-rounded" type="submit" value="Update">
        </div>

          </div>
          </div>
          </div>

          <script>
function calculateFinalAmount() {
    let total = parseFloat(document.getElementById('total').value) || 0;
    let discount = parseFloat(document.getElementById('discount').value) || 0;
    let finalAmount = total - discount;
    document.getElementById('final_amount').value = finalAmount.toFixed(2);
}
document.getElementById('total').addEventListener('input', calculateFinalAmount);
document.getElementById('discount').addEventListener('input', calculateFinalAmount);
</script>