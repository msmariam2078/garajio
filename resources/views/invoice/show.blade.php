<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tax Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #fff;
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

        .section {
            text-align: center;
            margin: 40px 0 20px;
        }

        .section h2 {
            font-size: 22px;
            font-weight: bold;
            text-decoration: underline;
        }

        .details-section {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .details-box {
            flex: 1;
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
            text-align: left;
        }

        table.quotation-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
            margin: 30px 0;
        }

        table.quotation-table th,
        table.quotation-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table.quotation-table th {
            background-color: #f2f2f2;
            text-align: left;
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

        .right-column .label {
            font-weight: bold;
        }

        .notes {
            font-size: 14px;
            color: #555;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        .buttons-section {
            text-align: center;
            margin: 30px 0;
        }

        .buttons-section .btnDesign {
            padding: 10px 20px !important;
            margin: 0 5px !important;
            font-size: 14px !important;
            cursor: pointer !important;
        }

        @media print {
            .buttons-section {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- HEADER -->
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

        <!-- COMPANY INFO -->
        <div style="text-align:center;margin:20px 0;">
            <div style="font-size:30px;"><strong>TAX INVOICE</strong></div>
        </div>

        <!-- CUSTOMER INFO -->
        <section class="details-section">
            <div class="details-box">
                <table>
                    <tr>
                        <td class="label">Customer:</td>
                        <td class="value">{{ !empty($invoice->clients) ? $invoice->clients->full_name : '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Address:</td>
                        <td class="value">
                            {{ !empty($invoice->client) && !empty($invoice->clients->clients) ? $invoice->clients->clients->service_address : '' }}
                            @if (!empty($invoice->clients) && !empty($invoice->clients->clients) && !empty($invoice->clients->clients->service_city))
                                <br>{{ $invoice->clients->clients->service_city }},
                                {{ $invoice->clients->clients->service_state }},
                                {{ $invoice->clients->clients->service_country }},
                                {{ $invoice->clients->clients->service_zip_code }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Tel No:</td>
                        <td class="value">
                            +{{ preg_replace('/[^0-9]/', '', $invoice->clients?->ccm) . $invoice->clients?->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td class="value">{{ !empty($invoice->clients) ? $invoice->clients->email : '' }}</td>
                    </tr>
                </table>
            </div>

            <div class="details-box">
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

        <!-- ITEMS TABLE -->
        <table class="quotation-table">
            <thead>
                <tr>
                    <th>Serial No.</th>
                    <th>Description</th>
                    <th>Qnty</th>
                    <th>Unit Price</th>
                    <th>Discount %</th>
                    <th>Sub Total</th>
                    <th>Tax Amount</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $totalamount = 0;
                    $discount = 0;
                    $tax = 0;
                @endphp
                @foreach ($quotations as $quotation)
                    @foreach ($quotation as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <strong>{{ $item->product_name }}</strong><br>
                                @if ($item->warranty_exists)
                                    <strong>Warranty:</strong> {{ $item->warranty_number }}<br>
                                    <span>Period: {{ \Carbon\Carbon::parse($item->from_date)->format('d M Y') }}
                                        to {{ \Carbon\Carbon::parse($item->to_date)->format('d M Y') }}
                                        ({{ \Carbon\Carbon::parse($item->to_date)->diffInMonths(\Carbon\Carbon::parse($item->from_date)) }}
                                        Months)
                                    </span>
                                @endif
                            </td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->gstprice }}</td>
                            <td>{{ number_format($item->linetotal, 2) }}</td>
                            <td>{{ number_format($item->taxamount, 2) }}</td>
                            <td>{{ number_format($item->totalamount, 2) }}</td>
                        </tr>
                        @php
                            $dis_int = (int) filter_var($item->gstprice, FILTER_SANITIZE_NUMBER_INT) ?? 0;
                            $unit_price = (float) ($item->unit_price ?? 0) * (int) ($item->qty ?? 1);
                            $discount += ($dis_int / 100) * $unit_price;
                            $subtotal += $item->linetotal;
                            $tax += $item->taxamount;
                            $totalamount += $item->totalamount;
                        @endphp
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <!-- TOTALS -->
        <section class="total-section">
            <div class="left-column"></div>
            <div class="right-column">
                <table>
                    <tr>
                        <td class="label">Sub Total:</td>
                        <td>{{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tax:</td>
                        <td>{{ number_format($tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Discount:</td>
                        <td>{{ number_format($discount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Total Due (AED):</strong></td>
                        <td><strong>{{ number_format($totalamount, 2) }}</strong></td>
                    </tr>
                </table>
            </div>
        </section>

        <!-- NOTES -->
        <section class="notes">
            <h4>Terms and Conditions:</h4>
                <ol>
                <li>Any discrepancies in Invoice or goods received should be highlighted in writing to "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" within 5 working days of receipt of invoice or Delivery Note, otherwise the Invoice will be treated as valid and payable in full.</li>
                <li>Goods cannot be returned once sold unless proven of manufacturing defect on proper inspection by both "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" and its "CUSTOMER".</li>
                <li>The primary responsibility of warranty services, if any, rests with the principal supplier or manufacturer.</li>
                <li>The Payment must be settled on the due date as per the Terms stipulated in the Invoices Submitted by "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC".</li>
                <li>In the case of any breach on the agreed payment terms, "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" has the right to suspend all supplies without prior notice and "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" has the right to recover its outstanding in full on first demand. Additionally, the buyer shall indemnify "DELTA TURF CARE AGRICULTURAL </li>
                <li>TRACTORS & MACHINERY TRADING LLC" for any Financial Damages suffered due to late payment, including but not limited to, attorney's fees and disbursements incurred to collect the overdue amount.</li>
            </ol>
        </section>

        <!-- FOOTER -->
        <!-- <h5><strong>* I hereby confirm receipt of the goods in good condition and agree to dispose of the old battery in
                compliance with UAE regulations through authorized channels, ensuring proper handling and
                recycling.</strong></h5> -->

        <section class="total-section" style="margin-bottom:30px;">
            <div class="left-column">
                <strong>Prepared By</strong>
                <br>
                <br>
                {{ $workorder->created_by && $workorder->agent->full_name
                    ? $workorder->agent->full_name
                    : auth()->user()->full_name }}
            </div>
            <div class="right-column">
                <strong>Sign</strong>
                <br>
                <br>
                {{ $invoice->clients->first_name }} {{ $invoice->clients->last_name }}
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

        <!-- BUTTONS -->
        <div class="buttons-section">
            <button class="btnDesign" id="email-invoice-btn">Email Invoice</button>
            <button class="btnDesign" id="email-invoice-btn"
                onclick="window.location.href='{{ url('/generate-invoice-pdf2/' . $invoice->id) }}'">
                Print Invoice
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.getElementById("print-invoice-btn").addEventListener("click", function() {
            var invoiceId = @json($invoice->id);
            //print();

            fetch(`/generate-invoice-pdf2/${invoiceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var printWindow = window.open(data.file_url, "_blank");
                        printWindow.addEventListener("load", function() {
                            printWindow.print();
                        });
                    } else {
                        alert("Failed to generate invoice for printing.");
                    }
                }).catch(error => {
                    console.error(error);
                    alert("Error generating the invoice.");
                });
        });
        document.getElementById("email-invoice-btn").addEventListener("click", function() {
            var invoiceId = @json($invoice->id);
            fetch(`/send-invoice-email2/${invoiceId}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": '{{ csrf_token() }}',
                    },
                }).then(response => response.json())
                .then(data => {
                    alert(data.success ? "Invoice has been emailed successfully." : "Failed to email invoice.");
                }).catch(error => {
                    console.error(error);
                    alert("Error emailing the invoice.");
                });
        });
    </script>

</body>

</html>
