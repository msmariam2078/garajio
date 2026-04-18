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
      max-width: 900px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #fff;
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

    /* Flexbox for Customer and Vehicle Details */
    .details-section {
        display: flex;
       
        margin-bottom: 20px;
    }

    .details-section .details-box {
        width: 48%;
        padding: 10px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    .details-box p {
        margin: 5px 0;
        font-size: 14px;
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
       .buttons-section {
            text-align: center;
            margin: 30px 0;
        }

        .buttons-section button {
            padding: 10px 20px;
            margin: 0 5px;
            font-size: 14px;
            cursor: pointer;
        }

        @media print {
            .buttons-section {
                display: none;
            }
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
   	     <h3>Delta Turf Care</h3>
            <p>P.O.Box: Nabra At | Almasani' | Riyadh</p>
            <p>info@deltaturfcare.com | www.deltaturfcare.com | Facebook.com/deltaturf | +996 114 333 458
            </p>

        </header>


        <section class="details-section">

            <div class="details-box">
                <p><strong>Customer:</strong>{{ $client->first_name }} {{ $client->last_name }}</p>
                <p><strong>Phone:</strong> +{{preg_replace('/[^0-9]/', '',  $client?->ccm )}}{{ $client->phone_number }}</p>
                <p><strong>Email:</strong> {{ $client->email }}</p>
      
                <p><strong>Payment Terms:</strong> CASH</p>
            </div>


            <div class="details-box">
                 <p><strong>Registration No.:</strong> {{ $vehicle->rego }}</p>
                <p><strong>Make:</strong> {{ $vehicle->vehicle_makes?->make_name }}</p>
                <p><strong>Model:</strong> {{ $vehicle->vehicle_models?->model_name }}</p>
                <p><strong>Year:</strong> {{ $vehicle->model_series }}</p>
            
                
            </div>
        </section>




        <section>
            <p><strong>Date:</strong> {{ optional($booking->created_at)->format('F j, Y') ?? '---' }}</p>
            <p><strong>Quotation Number:</strong> #QOT{{ $quotation->id }}</p>
        
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
                    $totalamount=0;
                    $tax = 0;
                   @endphp
                @foreach ($products as $product)
                  
                <tr class="item">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->qty }}</td>
                    <td>{{ number_format($product->unit_price , 3) }}</td>
                    <td>{{ $product->gstprice  , 3 }}</td>
                    <td>{{ number_format($product->linetotal , 3) }}</td>
                    <td>{{ $product->taxpercentage  }}</td>
                    <td>{{ number_format($product->taxamount , 3) }}</td>
                    <td>{{ number_format($product->totalamount , 3) }}</td>
                </tr>
                     @php
                            $dis_int = (int) filter_var($product->gstprice, FILTER_SANITIZE_NUMBER_INT) ?? 0;
                            $unit_price = (float) ($product->unit_price ?? 0) * (int) ($product->qty ?? 1);
                            $discount += ($dis_int / 100) * $unit_price;
                            $subtotal += $product->linetotal;
                            $tax += $product->taxamount;
                             $totalamount+=$product->totalamount;


  
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
                    <td class="value">{{ number_format($subtotal, 3) }}</td>
                </tr>
                <tr>
                    <td class="label">Tax :</td>
                    <td class="value">{{number_format($tax,3)}}</td>
                </tr>
                <tr>
                    <td class="label">Discount:</td>
                    <td class="value">{{number_format($discount,3)}}</td>
                </tr>
                <tr>
                    <td class="label"><strong>Total Due:</strong></td>
                    <td class="value"><strong>AED {{number_format($totalamount,3)}}</strong></td>
                </tr>
            </table>
</section>
            
       


        <!-- Notes Section -->
        <section style="width:100%">
            <p><strong>Notes:</strong></p>
            <ul>
                <li>Should you require any further clarifications, please do not hesitate to call the undersigned.</li>
                <li>We hope our offer meets with your requirement and look forward to your approval..</li>
                <li>This Quotation is considered only as a call for the Sale Deal and does not constitute a final Offer Price.</li>
            
            </ul>
        </section>

        <!-- Footer Section -->
        <footer class="footer">
            <!-- <p>I undersigned hereby authorize AMAP Auto Studio to execute the repair on my vehicle for the net total
                mentioned here. I also agree that any other parts/works found necessary to be replaced/done during the
                repairs will be charged extra.</p> -->
            <p class="h5">Prepared By: {{\Auth::user()->full_name}}</p>
        </footer>

        <!-- Buttons Section -->
        <section class="buttons-section d-none" style="text-align: center; margin-top: 20px;">
            <!-- <button id="generate-invoice-btn"
                style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; margin-right: 10px;">
                Generate Invoice PDF
            </button> -->
        <div class="buttons-section">
            
            <button id="email-invoice-btn"
                style="padding: 10px 20px; background-color: #008CBA; color: white; border: none; cursor: pointer; margin-right: 10px;">
                Email Quotation
            </button>
            <button id="print-invoice-btn"
                style="padding: 10px 20px; background-color: #f44336; color: white; border: none; cursor: pointer;">
                Print Quotation
            </button>
        </div>
        </section>

    </div>
</body>



<script>
// document.getElementById("generate-invoice-btn").addEventListener("click", function() {
//     // Replace with the booking ID dynamically
//     var bookingId = {
//         {
//             $booking - > id
//         }
//     };

//     // Send request to generate PDF
//     fetch(`/generate-invoice-pdf/${bookingId}`)
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 // Get the URL of the generated PDF
//                 var fileUrl = data.file_url;

//                 // WhatsApp API URL with the message
//                 var whatsappUrl =
//                     `https://wa.me/?text=Download%20your%20invoice%20from%20this%20link:%20${encodeURIComponent(fileUrl)}`;

//                 // Open WhatsApp share URL
//                 window.open(whatsappUrl, "_blank");
//             } else {
//                 alert("Failed to generate invoice.");
//             }
//         })
//         .catch(error => {
//             console.error("Error:", error);
//             alert("There was an error generating the invoice.");
//         });
// });
document.getElementById("email-invoice-btn").addEventListener("click", function() {
    //Replace with the booking ID dynamically

    var quoteId = @json( $quotation->id );

    // Send request to email invoice
    fetch(`/send-invoice-email/${quoteId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Quotation has been emailed successfully.");
            } else {
                alert("Failed to email Quotation.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("There was an error emailing the Quotation.");
        });
});
document.getElementById("print-invoice-btn").addEventListener("click", function() {

document.getElementById("print-invoice-btn").classList.display='none';
    // var bookingId =  @json($booking->id);
print();
    // Fetch the PDF generation endpoint
//     fetch(`/generate-invoice-pdf/${bookingId}`)
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 // Open the generated PDF in a new tab
//                 var fileUrl = data.file_url;
//                 var printWindow = window.open(fileUrl, "_blank");

//                 // Wait for the PDF to load and trigger the print dialog
//                 printWindow.addEventListener("load", function() {
//                     printWindow.print();
//                 });
//             } else {
//                 alert("Failed to generate invoice for printing.");
//             }
//         })
//         .catch(error => {
//             console.error("Error:", error);
//             alert("There was an error generating the invoice.");
//         });
});
</script>

</html>