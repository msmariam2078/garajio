<div class="modal-body">
    <form method="POST" action="{{ route('payment.update', $payments->id) }}" accept-charset="UTF-8"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group col-md-12">
                <label for="client" class="form-label">{{ __('Customer') }}</label>
                <select id="client" name="client" class="form-control hidesearch" onchange="getInvoices(this.value)"
                    required>
                    <option value="">Select Customer</option>
                    @foreach ($clients as $key => $value)
                    <option value="{{ $key }}" {{ $key== $payments->client ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12">
                <label for="invoice_id">Invoice</label>
                <select id="invoice_id" class="" name="invoice[]" multiple>
    <option value="">Select Invoice</option>
    @foreach ($invoices as $invoice)
        @if (in_array($invoice->id, $selectedInvoiceIds))
            <option value="{{ $invoice->id }}"
                {{ in_array($invoice->id, old('invoice', $selectedInvoiceIds)) ? 'selected' : '' }}>
                Invoice#{{ $invoice->total - $invoice->id }} - Total: {{ $invoice->total - $invoice->discount }}
            </option>
        @endif
    @endforeach
</select>

            </div>

            <div class="form-group col-md-6">
                <label for="invoice_date" class="form-label">
                    Amount Method
               
                </label>
                <select id="payment_method" class="" name="payment_method" required>
                    <option value="Cash"
                        {{ old('payment_method', $payments->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Online"
                        {{ old('payment_method', $payments->payment_method) == 'Online' ? 'selected' : '' }}>Online
                    </option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="invoice_date" class="form-label">
                    Amount
               
                </label>
                <input type="text" id="paid_amount" name="paid_amount"
                    value="{{ old('paid_amount', $payments->paid_amount) }}" class="form-control" required>
            </div>
            <div class="form-group col-md-12">
                <label for="service_location" class="form-label">Note</label>
                <textarea class="form-control" name="description" id="description"
                    rows="3">{{ old('description', $payments->description) }}</textarea>
            </div>
            <div class="form-group col-md-12">
                <label for="exampleFormControlSelect1">Status</label>
                <select class="" name="status">
                    <option value="">Select Status</option>
                    <option value="Completed" {{ $payments->status == 'Completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="Quote" {{ $payments->status == 'Quote' ? 'selected' : '' }}>Quote</option>
                    <option value="Booking" {{ $payments->status == 'Booking' ? 'selected' : '' }}>Booking</option>
                    <option value="Invoiced" {{ $payments->status == 'Invoiced' ? 'selected' : '' }}>Invoiced</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <input class="btn btn-primary btn-rounded" type="submit" value="Update">
        </div>
    </form>
</div>
<script>
    function getInvoices(clientId) {
        // Check if a valid client is selected
        if (clientId == '00') {
            document.getElementById('invoice_id').innerHTML = '<option value="">Select Invoice</option>';
            return;
        }

        // Make an AJAX request to fetch the invoices for the selected client
        fetch(`/get-invoices?client_id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                // Clear the invoice select box
                let invoiceSelect = document.getElementById('invoice_id');
                invoiceSelect.innerHTML = '<option value="">Select Invoice</option>';

                // Populate the invoice select box with the retrieved invoices
                data.forEach(invoice => {
                    let option = document.createElement('option');
                    option.value = invoice.id;

                    let total = Number(invoice.total) - Number(invoice.discount);

                    option.textContent = `Invoice #${invoice.id} - Total: ${total.toFixed(2)}`;

                    invoiceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching invoices:', error);
            });
    }
    $(document).ready(function() {
        $("select").selectize();
    });

</script>