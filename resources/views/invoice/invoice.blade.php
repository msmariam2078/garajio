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
        width: 60%;
        margin: auto;
        padding: 20px;
        border: 1px solid #ddd;
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
        justify-content: space-between;
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
            <h1>AMAP AUTO CARE L.L.C</h1>
            <p>P: 800 24 7 365 | E: info@autostudio.ae</p>
            <p>P.O. Box: 60235, Warehouse No. S1 & S2, Al Quoz Industrial Area No. 3</p>
        </header>


        <section class="details-section">

            <div class="details-box">
                <p><strong>Customer:</strong>{{ $client->first_name }} {{ $client->last_name }}</p>
                <p><strong>Phone:</strong> {{ $client->phone_number }}</p>
                <p><strong>Email:</strong> {{ $client->email }}</p>
                <p><strong>TRN No:</strong> —</p>
                <p><strong>Registration No:</strong> {{ $vehicle->rego }}</p>
                <p><strong>Payment Terms:</strong> CASH</p>
            </div>


            <div class="details-box">
                <p><strong>Model:</strong> {{ $vehicle->vehicle_models->model_name }}</p>
                <p><strong>Year:</strong> —</p>
                <p><strong>VIN Number:</strong> -</p>
                <p><strong>Mileage:</strong> —</p>
            </div>
        </section>




        <section>
            <p><strong>Date:</strong> {{ optional($booking->requested_date)->format('F j, Y') ?? '---' }}</p>
            <p><strong>Quotation Number:</strong> #QOT{{ $booking->id }}</p>
            <p><strong>Sales Advisor:</strong> SHAIK.SULTAN</p>
        </section>


        <table class="quotation-table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Disc Price</th>
                    <th>Sub Total</th>
                    <th>VAT</th>
                    <th>Ext Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="item">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->qty }}</td>
                    <td>{{ number_format($product->line_total, 2) }}</td>
                    <td>0</td>
                    <td>{{ number_format($product->line_total, 2) }}</td>
                    <td>0</td>
                    <td>{{ number_format($product->line_total, 2) }}</td>
                </tr>
               
                @endforeach
                <!-- Add more rows here -->
            </tbody>
        </table>

        <!-- Total Section -->
        <section class="total-section">
            <p><strong>Subtotal Excl. VAT:</strong> 00</p>
            <p><strong>VAT Subtotal:</strong>00</p>
            <p><strong>Total:</strong> 00</p>
            <p><strong>Discount:</strong> 00</p>
            <p><strong>Total in Words:</strong> 00 Dirhams only.</p>
        </section>


        <!-- Notes Section -->
        <section>
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
            <p>I undersigned hereby authorize AMAP Auto Studio to execute the repair on my vehicle for the net total
                mentioned here. I also agree that any other parts/works found necessary to be replaced/done during the
                repairs will be charged extra.</p>
            <p>Prepared By: SHAIK.SULTAN</p>
        </footer>

        <!-- Buttons Section -->
        <section class="buttons-section" style="text-align: center; margin-top: 20px;">
            <button id="generate-invoice-btn"
                style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; margin-right: 10px;">
                Generate Invoice PDF
            </button>
            <button id="email-invoice-btn"
                style="padding: 10px 20px; background-color: #008CBA; color: white; border: none; cursor: pointer; margin-right: 10px;">
                Email Invoice
            </button>
            <button id="print-invoice-btn"
                style="padding: 10px 20px; background-color: #f44336; color: white; border: none; cursor: pointer;">
                Print Invoice
            </button>
        </section>

    </div>
</body>



<script>
document.getElementById("generate-invoice-btn").addEventListener("click", function() {
    // Replace with the booking ID dynamically
    var bookingId = {
        {
            $booking - > id
        }
    };

    // Send request to generate PDF
    fetch(`/generate-invoice-pdf/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Get the URL of the generated PDF
                var fileUrl = data.file_url;

                // WhatsApp API URL with the message
                var whatsappUrl =
                    `https://wa.me/?text=Download%20your%20invoice%20from%20this%20link:%20${encodeURIComponent(fileUrl)}`;

                // Open WhatsApp share URL
                window.open(whatsappUrl, "_blank");
            } else {
                alert("Failed to generate invoice.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("There was an error generating the invoice.");
        });
});
document.getElementById("email-invoice-btn").addEventListener("click", function() {
    // Replace with the booking ID dynamically
    var bookingId = {
        {
            $booking - > id
        }
    };

    // Send request to email invoice
    fetch(`/send-invoice-email/${bookingId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Invoice has been emailed successfully.");
            } else {
                alert("Failed to email invoice.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("There was an error emailing the invoice.");
        });
});
document.getElementById("print-invoice-btn").addEventListener("click", function() {
    var bookingId = {
        {
            $booking - > id
        }
    };

    // Fetch the PDF generation endpoint
    fetch(`/generate-invoice-pdf/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Open the generated PDF in a new tab
                var fileUrl = data.file_url;
                var printWindow = window.open(fileUrl, "_blank");

                // Wait for the PDF to load and trigger the print dialog
                printWindow.addEventListener("load", function() {
                    printWindow.print();
                });
            } else {
                alert("Failed to generate invoice for printing.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("There was an error generating the invoice.");
        });
});
</script>

</html>