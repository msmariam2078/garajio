<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Invoice Quotation</title>

    <!-- Favicon -->
    <link rel="icon" href="./images/favicon.png" type="image/x-icon" />

    <!-- Invoice styling -->
    <style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.5;
    }

        .header {
            display: flex;
            justify-content: space-between;
        }

        .header .logo img {
            max-width: 300px;
        }

        .header .invoice-meta {
            text-align: right;
            font-size: 14px;
        }

    /* Flexbox for Customer and Vehicle Details */
    .details-section {
    
        margin-bottom: 40px;
    }

    .details-section .details-box {
        width: 40%;
        padding: 10px;
     
        background-color: white;
    }

    .details-box p {
        margin: 5px 0;
        font-size: 14px;
    }
    
        .details-box table {
            border-collapse: collapse;
            width: 100%;
        }

        .details-box td.label {
            font-weight: bold;
            padding: 5px;
            text-align: left;
        }

        .details-box td.value {
            padding: 5px;
            t}

    /* Table */
    .quotation-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .quotation-table th,
    .quotation-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
        font-size: 14px;
    }

    .quotation-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }

    .quotation-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
       .total-section {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .left-column {
            flex: 1;
            font-size: 15px;
        }

        .right-column {
            flex: 1;
            font-size: 15px;
            text-align: right;
        }

        .right-column table {
            float: right;
            border-collapse: collapse;
        }

        .right-column td {
            padding: 4px 8px;
        }

    .total-section p,
    .notes-section ul li {
        font-size: 14px;
    }

    .footer {
        text-align: left;
        font-size: 14px;
        margin-top: 20px;
    }

    .footer p {
        margin: 5px 0;
    }
    </style>
</head>


<body>
    <div class="container">

  <header class="header">
            <div class="logo">
                <img src="{{ asset('assets/invoice.png') }}" alt="Company Logo" width="170">
            </div>
            <div class="invoice-meta">
                <div><strong>Invoice Number:</strong> {{ $invoice->id }}</div>
                <div>Invoice Date: {{ $invoice->invoice_date }}</div>
                <div>TRN No.: none</div>
                <div>Page No.: 1 of 1</div>
            </div>
        </header>

  <div style="text-align:center;margin:20px 0;">
            <div style="font-size:30px;"><strong>TAX INVOICE</strong></div>
        </div>
        <section class="details-section" style="position:relative;height:20%">

            <div class="details-box" style="position:absolute;left:0">
               <table>
                    <tr>
                        <td class="label">Customer:</td>
                        <td class="value">{{ !empty($invoice->clients) ? $invoice->clients->full_name : '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Address:</td>
                        <td class="value">
                            {{ !empty($invoice->clients) && !empty($invoice->clients->clients) ? $invoice->clients->clients->service_address : '' }}
                            @if (
                                !empty($invoice->clients) &&
                                    !empty($invoice->clients->clients) &&
                                    !empty($invoice->clients->clients->service_city))
                                <br>{{ $invoice->clients->clients->service_city }},
                                {{ $invoice->clients->clients->service_state }},
                                {{ $invoice->clients->clients->service_country }},
                                {{ $invoice->clients->clients->service_zip_code }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Tel No:</td>
                        <td class="value">{{ !empty($invoice->clients) ? $invoice->clients->phone_number : '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td class="value">{{ !empty($invoice->clients) ? $invoice->clients->email : '' }}</td>
                    </tr>
                </table>
            </div>


            <div class="details-box" style="position:absolute;right:0">
             <table>
                    @foreach ($vehicles as $key => $vehicle)
                        <tr>
                            <td class="label">Registration No:</td>
                            <td class="value">{{ $vehicle->rego }}</td>
                        </tr>
                        <tr>
                            <td class="label">Make:</td>
                            <td class="value">{{ $vehicle->vehicle_makes ? $vehicle->vehicle_makes->make_name : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Model:</td>
                            <td class="value">
                                {{ $vehicle->vehicle_models ? $vehicle->vehicle_models->model_name : '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Year:</td>
                            <td class="value">{{ $vehicle->vehicle_models ? $vehicle->model_series : '' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="label">Technician:</td>
                        <td class="value">{{ count($technician) > 0 ? $technician[0]->full_name : '' }}</td>
                    </tr>
                </table>
            </div>
        </section>




       
   <section >

        <table class="quotation-table">
            <thead>
            <tr>
										<th scope="col">Item no</th>
										<th scope="col">Description</th>
										<th scope="col">Warranty</th>
										<th scope="col">Warranty Period</th>
										<th scope="col">Qnty</th>
										<th scope="col">Unit price</th>
										<th scope="col">Discount %</th>
										<th scope="col">Sub total</th>
										<th scope="col">Tax %</th>
										<th scope="col">Tax amount</th>
										<th scope="col">Total amount</th>
									</tr>
            </thead>
            <tbody>
                        
                                                      
            @php  $subtotal=0; @endphp
                                @foreach ($quotations as $quotation)
                                                @foreach ($quotation as $item)
                                                    <tr>
                                                       
                                                        <td>{{ $item->product_id}}</td>
                                                        <td>{{ $item->product_name }}</td>
                                                        <td>{{ $item->warrenty }}</td>
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
                                                   $subtotal= $subtotal +(doubleval($item->unit_price )?? 0) *(int)($item->qty ?? 1) ;
                                                  
                                                   @endphp 
                                                @endforeach
                                            @endforeach                                      
                                                   
         </tbody>
        </table>
   </section>
        <!-- Total Section -->
        <section class="total-section text-right" style="text-align:right">
            
        
								
								
						
        </section>


        <!-- Notes Section -->
        <section>
        <h3 style="font-weight:bold">Terms & Conditions:</h3><br>
                <ol>
                <li>Any discrepancies in Invoice or goods received should be highlighted in writing to "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" within 5 working days of receipt of invoice or Delivery Note, otherwise the Invoice will be treated as valid and payable in full.</li>
                <li>Goods cannot be returned once sold unless proven of manufacturing defect on proper inspection by both "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" and its "CUSTOMER".</li>
                <li>The primary responsibility of warranty services, if any, rests with the principal supplier or manufacturer.</li>
                <li>The Payment must be settled on the due date as per the Terms stipulated in the Invoices Submitted by "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC".</li>
                <li>In the case of any breach on the agreed payment terms, "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" has the right to suspend all supplies without prior notice and "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" has the right to recover its outstanding in full on first demand. Additionally, the buyer shall indemnify "DELTA TURF CARE AGRICULTURAL </li>
                <li>TRACTORS & MACHINERY TRADING LLC" for any Financial Damages suffered due to late payment, including but not limited to, attorney's fees and disbursements incurred to collect the overdue amount.</li>
            </ol>
        </section>
<!-- <h5><strong>* I hereby confirm receipt of the goods in good condition and agree to dispose of the old battery in
                compliance with UAE regulations through authorized channels, ensuring proper handling and
                recycling.</strong></h5> -->

        <section class="total-section" style="margin-bottom:30px;">
            <div class="left-column">
				<strong>Prepared By</strong>
				<br>
				<br>
				{{auth()->user()->first_name}} {{auth()->user()->last_name}} 
			</div>
            <div class="right-column">
				<strong>Sign</strong>
				<br>
				<br>
				{{$invoice->clients->first_name}} {{$invoice->clients->last_name}} 
                            </div>
        </section>

        <hr style="height:5px;background:yellow;margin:0;">
        <hr style="height:5px;background:blue;margin:0;">
        <section style="text-align:center;">
            <h3>Delta Turf Care</h3>
            <p>P.O.Box: Nabra At | Almasani' | Riyadh</p>
            <p>info@deltaturfcare.com | www.deltaturfcare.com | Facebook.com/deltaturf | +996 114 333 458
            </p>
        </section>
        <!-- Footer Section -->
        <footer class="footer">
            <p>I hereby confirm receipt of the goods in good condition and agree to dispose of the old battery in compliance with UAE regulations through authorized channels, ensuring proper handling and recycling.</p>

        </footer>


    </div>
</body>