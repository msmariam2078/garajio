@extends('layouts.master')
@section('css')


<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />

<link href="{{URL::asset('assets/plugins/accordion/accordion.css')}}" rel="stylesheet" />

<style>
.select-button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.select-button:hover {
    background-color: #45a049;
}
</style>
@endsection
@section('page-header')
<!-- breadcrumb -->



<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Payment</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Table</span>
        </div>
    </div>


</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('messages_alert')
<style>
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: red;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked+.slider {
    background-color: green;
}

input:checked+.slider:before {
    transform: translateX(26px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.toggle-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.toggle-option {
    font-weight: bold;
}

#sidebar-alert {
    width: 300px;
    /* Adjust based on your sidebar width */
    z-index: 1050;
    /* Ensure it’s above other elements */
}

/* Modal backdrop */
/* Modal backdrop */
.modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    /* 5% from the top and centered */
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    /* Responsive width */
    max-width: 800px;
    /* Max width for large screens */
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table,
th,
td {
    border: 1px solid #ddd;
}

th,
td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Style for selected row */
tbody tr.selected {
    background-color: #d1e7ff;
    /* Light blue background for selected row */
}

/* Positioning the Select button at the bottom right */
.modal-footer {
    text-align: right;
}

.select-button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.select-button:hover {
    background-color: #45a049;
}
</style>

<style>
.order-summary {
    width: 100%;
    max-width: 400px;
    margin-left: auto;

}

.form-control {
    display: inline-block;
}

.suggestion-box {
    border: 1px solid #ccc;
    max-height: 150px;
    overflow-y: auto;
    background: white;
    position: absolute;
    z-index: 1000;
    width: calc(100% - 2px);
}

.suggestion-box li {
    padding: 8px;
    cursor: pointer;
}

.suggestion-box li:hover {
    background-color: #f0f0f0;
}
</style>
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <h2 class="text-xl font-semibold mb-4">{{ __('Invoice Details') }}</h2>

        <table>
            <thead>
                <tr>
                    <th>{{ __('Invoice ID') }}</th>
                    <th>{{ __('Invoice Date') }}</th>
                    <th>{{ __('Total') }}</th>
                    <th>{{ __('Discount') }}</th>
                    <th>{{ __('Total Amount Due') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr onclick="selectInvoice(this)">
                    <td>{{ $invoice->invoice_id }}
                        <input type="hidden" class="invoice-id" value="{{ $invoice->id }}" />
                    </td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>{{ $invoice->total }}</td>
                    <td>{{ $invoice->discount }}</td>
                    <td>{{ $invoice->final_amount }}</td>
                </tr>
            </tbody>
        </table>



        <div class="modal-footer">
            <button onclick="addSelectedInvoice()" class="select-button">Select</button>
        </div>


    </div>
</div>
<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-2 customer-header">Customer Payment</h5>
                </div>
            </div>
            <div class="card-body">

                <div class="row">

                    <input value="{{$workOrder->id}}" type="hidden" id="workorder_id" />
                    <input value="admin" type="hidden" id="source" />
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customerOrderNumber">Post Date</label>
                            <input type="text" class="form-control" id="customerOrderNumber"
                                placeholder="Enter customer order number" value="{{$workOrder->created_date}}" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bookingDate">Refrence</label>
                            <input type="text" class="form-control" id="jobCardNo"
                                placeholder="Enter customer order number" value="#WO-{{$workOrder->id}}" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bookingDate">Image</label>
                            <input type="file" class="form-control" id="image"
                                name="image" placeholder="Enter customer order number">
                        </div>
                    </div>

                </div>



                <div class="mt-4">

                    <table class="table table-bordered mt-2">
                        <thead class="bg-primary mt-2" style="color:white;">
                            <tr>

                                <th style="color:white;">Refrence</th>
                                <th style="color:white;">Post Date</th>
                                <th style="color:white;">Invoice Amount</th>
                                <th style="color:white;">Balance Due</th>
                                <th style="color:white;">Applied Amount</th>
                                <th style="color:white;">Balance</th>

                            </tr>
                        </thead>
                        <tbody id="invoicetbody"></tbody>
                    </table>
                    <div class="mb-3 mt-3">
                        <button type="button" class="btn btn-primary" id="myBtn">
                            Add Invoice
                        </button>
                    </div>
                    <div class="order-summary bg-white shadow-sm p-4 border rounded">
                        <h5 class="text-center mb-4">Order Summary</h5>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="font-weight-bold text-muted">Applied Total:</span>
                            <span id="subtotal" class="font-weight-bold">$0.00</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="font-weight-bold text-muted">Balance:</span>
                            <span id="total" class="font-weight-bold">$0.00</span>
                        </div>

                        <div id="paymentDetails" class="mt-4">
                            <!-- Payment details will be dynamically inserted here -->
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="paymentMethod">Payment Method</label>
                            <select class="form-control" id="paymentMethod">
                                <option value="">Select Payment Method</option>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="tabby">Tabby</option>
                                <option value="online_pay">Online Pay</option>
                                <option value="bank">Bank</option>
                                <option value="credit">Credit</option>
                                <option value="tamara">Tamara</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" placeholder="Enter amount">
                        </div>
                    </div>
                        <div class="col-md-3">
                        <div class="form-group">
                            <label for="Reference">Reference Number</label>
                            <input type="text" class="form-control" id="reference" placeholder="Enter Reference Number">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary mt-4" onclick="addPaymentMethod()">Add
                                Payment</button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-12">
                    <button type="button" onclick="submitPayment()" class="btn btn-primary">Pay Now</button>
                    <button type="button" onclick="" class="btn btn-primary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@section('js')
<script>
var modal = document.getElementById("myModal");

var btn = document.getElementById("myBtn");


var span = document.getElementsByClassName("close")[0];


btn.onclick = function() {
    modal.style.display = "block";
}

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function selectInvoice(row) {

    row.classList.toggle("selected");
}


let selectedInvoices = [];
let totalPaidAmount = 0;

let balance = 0;

function selectInvoice(row) {
    let rows = document.querySelectorAll('tbody tr');
    rows.forEach(r => r.classList.remove("selected"));
    row.classList.add("selected");
    const invoiceId = row.querySelector('.invoice-id').value;


    selectedInvoice = {
        id: invoiceId,
        reference: row.cells[0].textContent,
        postDate: row.cells[1].textContent,
        balanceDue: parseFloat(row.cells[2].textContent.replace('$', '').trim()),
        appliedAmount: parseFloat(row.cells[3].textContent.replace('$', '').trim()),
        balance: parseFloat(row.cells[4].textContent.replace('$', '').trim())
    };
}


function addSelectedInvoice() {
    if (!selectedInvoice) {
        alert('Please select an invoice first.');
        return;
    }
  
    const tbody = document.getElementById("invoicetbody");
    const newRow = document.createElement("tr");
  if(document.querySelector(`#invoicetbody tr`))
  {
    alert('Invoice aleady selected');
    closeModal();
    return;
  }
    newRow.innerHTML = `
        
        <td>${selectedInvoice.reference}</td>
        <td>${selectedInvoice.postDate}</td>
        <td>${selectedInvoice.balanceDue.toFixed(2)}</td>
         <td>${selectedInvoice.balanceDue.toFixed(2)}</td>
        <td>${selectedInvoice.appliedAmount.toFixed(2)}</td>
        <td>${selectedInvoice.balance.toFixed(2)}</td>
    `;

    tbody.appendChild(newRow);
    closeModal();

    selectedInvoices.push(selectedInvoice);
    document.getElementById('amount').value=selectedInvoice.balanceDue.toFixed(2);

    updateSummary();
}

function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

function updateInvoiceAmount(input, id) {
    const newAppliedAmount = parseFloat(input.value);

    const invoice = selectedInvoices.find(invoice => invoice.id === id);
    if (invoice) {
        invoice.appliedAmount = newAppliedAmount;
        invoice.balance = invoice.balanceDue - newAppliedAmount;

        const row = input.closest('tr');
        row.cells[4].textContent = `$${invoice.balance.toFixed(2)}`;
    }

    updateSummary();
}

function updateSummary() {
    let appliedTotal = selectedInvoices.reduce((sum, invoice) => sum + invoice.appliedAmount, 0);
    let balanceTotal = selectedInvoices.reduce((sum, invoice) => sum + invoice.balance, 0);

    document.getElementById("subtotal").textContent = `${appliedTotal.toFixed(2)}`;
    document.getElementById("total").textContent = `${balanceTotal.toFixed(2)}`;
    document.getElementById("amount").value = balanceTotal.toFixed(2);
    document.getElementById("balanceAmount").textContent = balanceTotal.toFixed(2);
    document.getElementById("reference").value = '';
    checkSubmitStatus();
}

function checkSubmitStatus() {
    //const totalPaidAmount = parseFloat(document.getElementById("totalPaidAmount").textContent);
  //  const totalBalanceAmount = parseFloat(document.getElementById("balanceAmount").textContent);

    if (totalPaidAmount >= totalBalanceAmount) {
        document.getElementById("submitPaymentButton").disabled = false;
    } else {
        document.getElementById("submitPaymentButton").disabled = true;
    }
}

function submitPayment() {
    if(payments.length==0)
    {
        alert("Payment not fully applied, please add payment first!");
        rerurn;
    }
    console.log("Submit Payment called");
    const workOrderId = parseInt(document.getElementById("workorder_id").value);
    const source = parseInt(document.getElementById("source").value);
    const totalPaid = payments.reduce((total, payment) => total + payment.amount, 0);
    const invoices = selectedInvoices.map(invoice => ({
        id: invoice.id,
        reference: invoice.reference,
        appliedAmount: invoice.appliedAmount,
        balance: invoice.balance
    }));

    const data = {
        workOrderId: workOrderId,
        source:source,
        totalPaid: totalPaid,
        invoices: invoices,
        paymentMethods: payments,
        balance: balance
    };


    const formData = new FormData();


    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            formData.append(key, JSON.stringify(data[key]));
        }
    }


    const imageFile = document.getElementById("image").files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }


    $.ajax({
        url: '/storepayment',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function(response) {
            var url;
            if (response.success) {
            //     if(response.source&&response.source=='admin')
            // {
                url='/workorder/' + response.workorderId + '/edit';
            // }else if((response.source&&response.source=='tech')){
            //     url='/workorder/details/'+response.workorderId ;
            // }
                Swal.fire({
                    title:'Payment successfully submitted!',
                  text: "INVOICE NO."+ response.invoiceId +" "+"Payment successfully submitted!",
                   icon: "success",
                       draggable: true
                })
                .then((willDelete) => {
                             if (willDelete) {
                                window.location.href = url;
                          } else {
                              swal("Your imaginary file is safe!");
                           }                          
                                     });
              
            
              
            } else {
                alert('There was an error submitting the payment.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('There was an error submitting the payment.');
        }
    });
}
  
let payments = [];

function updateBalance(newBalance) {
    balance = newBalance;
    document.getElementById("total").textContent = balance.toFixed(2);
    checkPaymentStatus();
}

function addPaymentMethod() {
    const paymentMethod = document.getElementById("paymentMethod").value;
    let amount = parseFloat(document.getElementById("amount").value);
     let reference =document.getElementById("reference").value;
    if (!paymentMethod) {
        alert('Please select a payment method.');
        return;
    }
    //  if (!reference) {
    //     alert('Please select a Reference Number.');
    //     return;
    // }

    if (isNaN(amount) || amount <= 0) {
        alert('Please enter a valid amount.');
        return;
    }

    // Calculate the total balance due across all selected invoices
    let totalBalanceDue = selectedInvoices.reduce((sum, invoice) => sum + (invoice.balanceDue -
     invoice.appliedAmount),
        0);

    // if (amount > totalBalanceDue) {
    //     alert('Payment amount cannot exceed the total balance due.');
    //     return;
    // }

    // Add the payment method and amount to the payments array
    const payment = {
        method: paymentMethod,
        amount: amount,
        reference : reference
    };
    payments.push(payment);
    console.log(payments);

    // Distribute the payment across selected invoices
    distributePayment(amount);

    // Update the payment display and reset fields
    displayPayments();
    updateSummary();

    // Reset input fields
    document.getElementById("paymentMethod").value = ''; // Clear payment method field
    document.getElementById("amount").value = ''; // Clear amount field
}

function distributePayment(amount) {
    // Distribute the payment across selected invoices
    for (let i = 0; i < selectedInvoices.length && amount > 0; i++) {
        let invoice = selectedInvoices[i];
        let availableBalance = invoice.balanceDue - invoice.appliedAmount;

        if (availableBalance > 0) {
            let appliedNow = Math.min(amount, availableBalance);
            invoice.appliedAmount += appliedNow;
            invoice.balance -= appliedNow;
            amount -= appliedNow;

            // Update the invoice table row dynamically
            let row = document.querySelector(`#invoicetbody tr:nth-child(${i + 1})`);
            if (row) {
                row.cells[4].textContent = `${invoice.appliedAmount.toFixed(2)}`; // Applied Amount
                row.cells[5].textContent = `${invoice.balance.toFixed(2)}`; // Remaining Balance
            }
        }
    }


}

function displayPayments() {

    const paymentDetails = document.getElementById("paymentDetails");
    paymentDetails.innerHTML = ''; // Clear existing payment details

    payments.forEach((payment, index) => {
        const paymentRow = document.createElement("div");
        paymentRow.classList.add("payment-row", "d-flex", "justify-content-between", "align-items-center", "mb-3", "border", "border-light", "p-3", "rounded", "bg-light");
        
        paymentRow.innerHTML = `
            <div>
                <strong>${payment.method}</strong>: <span class="text-success">${payment.amount.toFixed(2)}</span><br>
                <span><strong>Reference No.</strong >${payment.reference}</span>
            </div>
            <div>
                <button type="button" class="btn btn-outline-danger btn-sm remove-payment d-flex align-items-center" data-index="${index}">
                    <i class="bi bi-x-circle-fill me-1"></i> Remove
                </button>
            </div>
        `;
        
        // Add event listener to the "Remove" button for removing the payment
        const removeButton = paymentRow.querySelector(".remove-payment");
        removeButton.addEventListener("click", () => removePayment(index));
        
        paymentDetails.appendChild(paymentRow);
    });

   
}
function removePayment(index) {
    // Get the amount of the payment to remove
   const removedPaymentAmount = payments[index].amount;

    // Remove payment from the payments array
    payments.splice(index, 1);

    // Revert the applied amounts on invoices
revertPayment(removedPaymentAmount);

    // Update the UI
    displayPayments();
    updateSummary();
}

function revertPayment(removedPaymentAmount) {
    let remainingAmountToRevert = removedPaymentAmount;

    // Reverse the applied amounts on selected invoices
    for (let i = 0; i < selectedInvoices.length && remainingAmountToRevert > 0; i++) {
        let invoice = selectedInvoices[i];
        let availableBalance = invoice.balanceDue - invoice.appliedAmount;

        if (availableBalance < invoice.balanceDue) {
            let reversedNow = Math.min(remainingAmountToRevert, availableBalance);
            invoice.appliedAmount -= reversedNow;
            invoice.balance += reversedNow;
            remainingAmountToRevert -= reversedNow;

            // Update the invoice table row dynamically
            let row = document.querySelector(`#invoicetbody tr:nth-child(${i + 1})`);
            if (row) {
                row.cells[4].textContent = `${invoice.appliedAmount.toFixed(2)}`; // Applied Amount
                row.cells[5].textContent = `${invoice.balance.toFixed(2)}`; // Remaining Balance
            }
        }
    }


}

// Handle removing a payment via the remove button
document.getElementById("paymentDetails").addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-payment')) {
        const paymentIndex = e.target.getAttribute('data-index');
        removePayment(paymentIndex);
    }
});


            // Update the invoice table row dynamically

</script>
















<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>
<script src="{{URL::asset('assets/js/custom-script.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="{{URL::asset('assets/plugins/accordion/accordion.min.js')}}"></script>
<script src="{{URL::asset('assets/js/accordion.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection