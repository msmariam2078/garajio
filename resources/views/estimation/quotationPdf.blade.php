

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

    .container {
        /* width: 60%;
        margin: auto;
        padding: 20px;
        border: 1px solid #ddd;
        background-color: #fff; */
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 24px;
        margin: 0;
    }

    .header p {
        margin: 5px 0;
        font-size: 14px;
    }

   .details-section {
    
       
    }

    .details-section .details-box {
        width: 40%;
        padding: 10px;
        border: 1px solid #ddd;
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

    .total-section p,
    .notes-section ul li {
        font-size: 14px;
    }
     
        table.invoice-table {
            text-align: right;
        }

        .invoice-table td.label {
            text-align: right;
            padding: 2px 10px;
            width: 40%;
        }

        .first .invoice-table td.label {
            text-align: left !important;
            padding: 2px 10px;
            width: 2% !important;
        }

        .details-box .invoice-table td.label {
            text-align: right;
            padding: 2px 10px;
            width: 5%;
        }

        .invoice-table td.value {
            text-align: left;
            padding: 2px 10px;
            width: 10%;
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
   	<h3 style="margin-bottom: 0px;">DIAL A BATTERY</h3>
			<p style="margin: 0px;">
				<strong>800 24 7 365</strong> | P.O.Box: 77563 | Dubai | United Arab Emirates |
			</p>
			<p style="margin: 0px;">info@dial-a-battery.com | www.dial-a-battery.com | Facebook.com/dialabattery | Instagram.com/dialabattery</p>
		
        </header>


        <div  class="details-section" style="position:relative;height:20%">

           <div class="details-box" style="position:absolute;left:0">
       
                <table>
                    <tr>
                        <td class="label">Customer:</td>
                        <td class="value">{{ $client->first_name }} {{ $client->last_name }}</td>
                    </tr>
                   
                    <tr>
                        <td class="label">Tel No:</td>
                        <td class="value">+{{preg_replace('/[^0-9]/', '',  $client?->ccm )}}{{ $client->phone_number }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td class="value">{{$client->email }}</td>
                    </tr>
                      <tr>
                        <td class="label">Payment Terms:</td>
                        <td class="value">Cash</td>
                    </tr>
                </table>
            </div>


            <div class="details-box" style="position:absolute;right:0">
           
               
               <table>
          
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
                
              
                </table>
            </div>
</div>




        <section>
            <p><strong>Date:</strong> {{ optional($booking->created_at)->format('F j, Y') ?? '---' }}</p>
            <p><strong>Quotation Number:</strong> #QOT{{ $booking->id }}</p>
        
        </section>

    <section style="width:100%">
        <table class="quotation-table">
            <thead>
                <tr>
                    <th>Serial No.</th>
                   
                    <th>Description</th>

                    <th>Qnty</th>
                    <th>Unit price</th>
                    <th>Discount %</th>
                    <th>Sub total</th>
                    <th>Tax %</th>
                    <th>Tax amount</th>
                    <th>Total amount</th>
                </tr>
            </thead>
            <tbody>
                    @php 
                       $subtotal=0; 
                        $discount=0;
                        $tax = 0;
                        $totalamount=0;
                   @endphp
                @foreach ($products as $product)
               
                <tr class="item">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->qty }}</td>
                    <td>{{ number_format($product->unit_price , 2) }}</td>
                    <td>{{ $product->gstprice  , 2 }}</td>
                    <td>{{ number_format($product->linetotal , 2) }}</td>
                    <td>{{ $product->taxpercentage  }}</td>
                    <td>{{ number_format($product->taxamount , 2) }}</td>
                    <td>{{ number_format($product->totalamount , 2) }}</td>
                </tr>
                     @php
                                                   $dis_int= (int) filter_var($product->gstprice, FILTER_SANITIZE_NUMBER_INT) ?? 0;
                                                   $unit_price=(doubleval($product->unit_price )?? 0) *(int)($product->qty ?? 1);
                                                   $discount= $discount + ($dis_int/100)* $unit_price ; 
                                               
                                                   $subtotal= $subtotal +$unit_price ;
                                                   
                                                   $tax= $tax + $product->taxamount ;
                                                   $totalamount=$totalamount+=$product->totalamount;
                             @endphp
                @endforeach
                <!-- Add more rows here -->
            </tbody>
        </table>
        <section>
    
       <!-- Right Side: Totals Table -->
        <section style="width:100%">
            <table class="invoice-table" style="width:100%;float:right">
                <tr>
                    <td class="label">Sub Total:</td>
                    <td class="value">{{ $subtotal }}</td>
                </tr>
                <tr>
                    <td class="label">Tax :</td>
                    <td class="value">{{$tax}}</td>
                </tr>
                <tr>
                    <td class="label">Discount:</td>
                    <td class="value">{{$discount}}</td>
                </tr>
                <tr>
                    <td class="label"><strong>Total Due:</strong></td>
                    <td class="value"><strong>{{$totalamount}}</strong></td>
                </tr>
            </table>
</section>
            
       


        <!-- Notes Section -->
        <section style="width:100%">
            <p><strong>Notes:</strong></p>
            <ul>
                <li>Supplementary will be submitted as required.</li>
                <li>Part prices are subject to change without prior notice.</li>
                <li>Parts prevailing at the time of delivery shall be changed.</li>
                <li>Delivery is subject to the availability of parts.</li>
                <li>VAT @ 5% is applicable.</li>
                <li>This estimate is valid for only 7 days.</li>
            </ul>
        </section>

        <!-- Footer Section -->
        <footer class="footer">
            <!-- <p>I undersigned hereby authorize AMAP Auto Studio to execute the repair on my vehicle for the net total
                mentioned here. I also agree that any other parts/works found necessary to be replaced/done during the
                repairs will be charged extra.</p> -->
            <p>Prepared By: {{\Auth::user()->full_name}}</p>
        </footer>

        <!-- Buttons Section -->
        <!-- <section class="buttons-section d-none" style="text-align: center; margin-top: 20px;"> -->
            <!-- <button id="generate-invoice-btn"
                style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; margin-right: 10px;">
                Generate Invoice PDF
            </button> -->
            <!-- <button id="email-invoice-btn"
                style="padding: 10px 20px; background-color: #008CBA; color: white; border: none; cursor: pointer; margin-right: 10px;">
                Email Invoice
            </button>
            <button id="print-invoice-btn"
                style="padding: 10px 20px; background-color: #f44336; color: white; border: none; cursor: pointer;">
                Print Invoice
            </button>
        </section> -->

    </div>
</body>
