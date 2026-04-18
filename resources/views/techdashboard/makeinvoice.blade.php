@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <link href="{{ URL::asset('assets/plugins/accordion/accordion.css') }}" rel="stylesheet" />
@endsection

<style>
	.heading-scroll {
		overflow-x: auto;
		white-space: nowrap;
	}

	/* Optional: makes link block wider so it overflows */
	.min-width-content {
		min-width: 400px; /* Adjust this as needed */
	}

	/* Only apply scroll on mobile */
	@media (max-width: 768px) {
		.heading-scroll {
			display: block;
		}
	}
</style>
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Invoice</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                    Table</span>
            </div>
        </div>


    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('messages_alert')
    <div class="modal fade" id="scrapModal" tabindex="-1" aria-labelledby="scrapModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrapModalLabel">Are you sure you want to create a invoice ?</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmScrap" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>
  



 

           

        
          


        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="text-center h2">Tax Invoice</div>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-8">
                            <b class="h6">Delta Turf Care</b>
                            <p class="mb-0">
                            Address:P.O.Box 77563-Dcity-country
                            </p>
                            <p class="mb-0">
                             Tel No:800247365
                            </p>
                            <p class="mb-0">
                             Email:info@delta-turf.com
                            </p>
                            <br>
                            <b class="h6 ">{{ $clientDetails->full_name }}</b>
                            <p class="mb-0">
                             Address:{{ $clientDetails->clients?->service_address }}
                            </p>
                            <p class="mb-0">
                             Tel No:{{ $clientDetails->phone_number }}
                            </p>
                            <p class="mb-5">
                             Email:{{ $clientDetails->email }}
                            </p>
                            <input type="hidden" value="{{ $clientDetails->id }}" class="form-control"
                            id="customerId">
                            <input type="hidden" value="{{ $workOrder->id }}" class="form-control" id="workOrderId">
                    
                        </div>
                        <div class="col-md-4">
                            <table class="table">
                                <tbody id="abc">
									<style>
										#abc tr td {padding: 0px !important;}
									</style>
                                    <tr>
                                        <td class="p-0">Invoice information</td>
                                        <td class="float-right p-0">GHT-6895</td>
                                    </tr>
                                    <tr>
                                        <td>Invoice no</td>
                                        <td class="float-right" >{{ $bookingReference }}</td>
                                  
                                    </tr>
                                    <tr>
                                        <td>Work order</td>
                                        <td class="float-right">#WO{{$workOrder->id}}</td>
                                    </tr>
                                    <tr>
                                        <td>Issue date</td>
                                        <td class="float-right">{{\Carbon\Carbon::today()}}</td>
                                    </tr>
                                    <tr>
                                     
                                        <input type="hidden" class="form-control" name="" id="postDate"
                                        value="{{ $bookings->requested_date  }}" readonly>
                                    </tr>
									
                                    @foreach ($vehicles as $key => $vehicle)
									<tr>
                                        <td>Make</td>
                                        <td class="float-right">{{ $vehicle->vehicle_makes?->make_name }}</td>
                                    </tr>
									<tr>
                                        <td>Model</td>
                                        <td class="float-right">{{ $vehicle->vehicle_models?->model_name }}</td>
                                    </tr>
                                    @endforeach
									<tr>
                                        <td>Year</td>
                                        <td class="float-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

					



                        <div class="col-md-12 heading-scroll">
							<table class="table text-center table-bordered min-width-content">
								<thead>
									<tr>
										<th scope="col">Item no</th>
										<th scope="col">Product ID</th>
										<th scope="col">Product Name</th>
										<th scope="col">Warranty Period</th>
										<th scope="col">Qty</th>
										<th scope="col">Unit price</th>
										<th scope="col">Discount %</th>
										<th scope="col">Sub total</th>
										<th scope="col">Tax %</th>
										<th scope="col">Tax amount</th>
										<th scope="col">Total amount</th>
									</tr>
								</thead>
								<tbody>
                                    @php  $subtotal=0; 
                                    $discount=0;
                                     $totalamount=0;
                                    $tax=0;
                                    @endphp
                                @foreach ($quotations as $quotation)
                                                @foreach ($quotation as $key =>$item)
                                                    <tr>
                                                       
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $item->item_no}}</td>
                                                        <td>{{ $item->product_name }}</td>
                                                        <td>{{ $item->warrenty }}</td>
                                                      
                                                        <td>{{ $item->qty }}</td>
                                                        <td>{{ $item->unit_price }}</td>
                                                      
                                                        <td>{{ $item->gstprice }}</td>
                                                        <td>{{ $item->linetotal }}</td>
                                                       
                                                        <td>{{ $item->taxpercentage }}</td>
                                                        <td>{{ $item->taxamount }}</td>
                                                        <td>{{ $item->totalamount }}</td>
                                                    </tr>
                                                   
                                                   @php
                                                  
                                                   $dis_int= (int) filter_var($item->gstprice, FILTER_SANITIZE_NUMBER_INT) ?? 0;
                                                   $unit_price=(doubleval($item->unit_price )?? 0) *(int)($item->qty ?? 1);
                                                   $discount= $discount + ($dis_int/100)* $unit_price ; 
                                               
                                                   $subtotal= $subtotal +$item->linetotal ;
                                                   
                                                   $tax= $tax + $item->taxamount ;
                                                  $totalamount+=$item->totalamount;
                                                  @endphp 
                                               @endforeach
                                           @endforeach
                               </tbody>
                           </table>
                       </div>

                       <div class="col-md-8">
                           <!-- Terms & condition -->
                       </div> 
                       <div class="col-md-4">
                       <div class="float-right  w-50">
                        <table class="table">
                                <tbody id="abc">
									<style>
										#abc tr td {padding: 0px !important;
                                        border: none}
									</style>
                                    <tr>
                                        <td>Sub total </td>
                                        <td> -</td>
                                        <td class="text-right">{{$subtotal}} AED </td>
                                    </tr>
                                    <tr>
                                        <td>Tax </td>
                                        <td> -</td>
                                        <td class="text-right">{{$tax}} AED</td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td> -</td>
                                        <td class="text-right">{{$discount}} AED</td>
                                    </tr>
                                    <tr>
                                        <td>Total Due </td>
                                        <td class="px-3"> -</td>
                                        <td class="text-right">{{$totalamount}} AED </td>
                                        <input type="hidden" class="form-control" name="totalamount" id="totalamount"
                                        value="{{ $totalamount}}" readonly>
                                    </tr>
                              </tbody>  
                       </table>
                       </div>
						</div>
                    </div>
                    <div class="btn-list mt-4 d-flex justify-content-end">
                    <a type="button" class="btn btn-secondary" id="" href="{{route('workorder.techdetails',$workOrder->id)}}">Cancel</a>
                       
                      
                        <button type="button" class="btn btn-success mx-2" id="startPayment">Process</button>
                        <a type="button" href="{{ url('transferpaymentinvoicetechnician/' . $workOrder->id) }}" id="paynow" class="btn btn-warning mx-2 d-none">Pay Now</a>
                      
                   
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("startPayment").addEventListener("click", function() {
            var scrapModal = new bootstrap.Modal(document.getElementById("scrapModal"));
            scrapModal.show();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const postDateInput = document.getElementById('postDate');
            const dueDateInput = document.getElementById('dueDateInput'); // Corrected ID reference
            const invoiceSwitch = document.getElementById('invoice-switch');
            const paymentTermsGroup = document.getElementById('payment-terms-group');
            const paymentTermsSelect = document.getElementById('payment-terms');

            // Set default behavior based on initial state
            if (!invoiceSwitch.checked) {
                paymentTermsGroup.style.display = 'none'; // Hide Payment Terms for Cash
                dueDateInput.value = postDateInput.value; // Set Due Date same as Post Date
            }

            // Handle switch toggle
            invoiceSwitch.addEventListener('change', function() {
                if (this.checked) {
                    // Bank selected
                    paymentTermsGroup.style.display = 'block';
                    updateDueDateBasedOnPaymentTerms();
                } else {
                    // Cash selected
                    paymentTermsGroup.style.display = 'none';
                    dueDateInput.value = postDateInput.value; // Set Due Date same as Post Date
                }
            });

            // Update Due Date when Payment Terms change
            paymentTermsSelect.addEventListener('change', updateDueDateBasedOnPaymentTerms);

            function updateDueDateBasedOnPaymentTerms() {
                const postDate = new Date(postDateInput.value);
                const additionalDays = parseInt(paymentTermsSelect.value, 10) || 0; // Default to 0 if not parsable

                if (!isNaN(postDate.getTime())) {
                    postDate.setDate(postDate.getDate() + additionalDays);
                    const formattedDate = postDate.toISOString().split('T')[0];
                    dueDateInput.value = formattedDate;
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            function validateFormData(formData) {
                let errors = [];
                // Uncomment these if validation is needed
                // if (!formData.invoiceNo) errors.push("Invoice No is required.");
                // if (!formData.customerOrderNumber) errors.push("Customer Order Number is required.");
                // if (!formData.jobCardNo) errors.push("Job Card No. is required.");
                // if (!formData.postDate) errors.push("Post Date is required.");
                // if (!formData.invoiceType) errors.push("Invoice Type is required.");
                // if (!formData.followUpDate) errors.push("Follow Up Date is required.");
                // if (!formData.odometer) errors.push("Odometer is required.");
                // if (!formData.hours) errors.push("Hours are required.");
                // if (!formData.nextServiceKms) errors.push("Next Service KMS is required.");
                // if (!formData.status) errors.push("Status is required.");
                // if (!formData.jobStatusComment) errors.push("Job Status Comment is required.");
                // if (!formData.customerSource) errors.push("Customer Source is required.");
                // if (!formData.description) errors.push("Description is required.");

                return errors;
            }

            function collectFormData() {
                return {
                    customerId: $('#customerId').val(),
                    workOrderId: $('#workOrderId').val(),
                    invoiceNo: $('#invoiceNo').val(),
                    customerOrderNumber: $('#customerOrderNumber').val(),
                    jobCardNo: $('#jobCardNo').val(),
                    postDate: $('#postDate').val(),
                    dueDate: $('#dueDateInput').val(),
                    invoiceType: $('#invoiceType').val(),
                    paymentTerms: $('#payment-terms').val(),
                    followUpDate: $('#followupDate').val(),
                    totalamount: $('#totalamount').val(),
                    nextServiceKms: $('#nextservice').val(),
                    status: $('#status').val(),
                    customerSource: $('#customersource').val(),
                    description: $('#description').val(),
                    paymentType: $('#invoice-switch').is(":checked") ? "Cheque" : "Cash"
                };
            }

            $('#confirmScrap').click(function() {
                let formData = collectFormData();
                let errors = validateFormData(formData);

                if (errors.length > 0) {
                    alert(errors.join("\n"));
                    return;
                }
                $.ajax({
                    url: "{{ route('invoice.finalstore') }}",
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                             alert("Invoice Created Successfully");
                             $("#startPayment").addClass("d-none");
                             $("#paynow").removeClass("d-none");
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(error) {
                        console.error(error);
                        alert("An error occurred while creating the invoice.");
                    }
                });
            });
        });
    </script>
@endsection
@section('js')
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets//plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('assets/js/custom-script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="{{ URL::asset('assets/plugins/accordion/accordion.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/accordion.js') }}"></script>
@endsection
