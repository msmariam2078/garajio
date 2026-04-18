<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tax Invoice</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }
        }

        body {
            font-size: 13px;
            color: #000;
            padding: 20px;
            max-width: 210mm;
            margin: auto;
            font-family: Arial, sans-serif;
        }

        .header {
            display: flex;
            justify-content: space-between;
        }

        .title {
            text-transform: uppercase;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid black;
            padding: 2px;
            vertical-align: top;
        }

        .borderless2 td,
        .borderless2 th {
            border: none;
            padding: 2px 5px;
        }

        .totals td {
            font-weight: bold;
        }

        .footer-note {
            font-size: 12px;
            font-style: italic;
        }

        .sign-box {
            margin-top: 40px;
        }

        .sign-box p {
            margin-bottom: 40px;
        }

        .text-sm {
            font-size: 12px;
        }

        .align-first-column-right td:first-child {
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mb-1 {
            margin-bottom: 10px;
        }

        .center {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .right {
            text-align: right !important;
        }
    </style>
</head>

<body>
    <div>
        <div style="position:relative;margin-bottom:20px">
            @php
                $path = public_path('assets/invoice.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            <img src="{{ $base64 }}" alt="Company Logo" width="200" style="margin-top:0">

            {{-- <img src="{{ public_path('assets/invoice.png') }}" alt="Company Logo" width="200" style="margin-top:0"> --}}
            <div style="position:absolute;right:0;top:0;">
                <div style="font-size:20px;font-weight:bold;text-align:right;padding-left:5px;margin-bottom:5px">
                    TAX INVOICE
                </div>
                <table class="custom-table borderless2">
                    <tr>
                        <td class="text-end"><strong>Invoice Number:</strong></td>
                        <td class="text-start">INV-{{ $invoice->id }}</td>
                    </tr>
                    <tr>
                        <td class="text-end"><strong>Invoice Date:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-end"><strong>TRN No:</strong></td>
                        <td>{{ $setting['trn_no'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="text-end"><strong>Page No:</strong></td>
                        <td>1 of 1</td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="position:relative;margin-bottom:3%;">
            <div style="border:1px solid black;width:45%">
                <table class="custom-table">
                    <tr>
                        <td><strong>Customer:</strong></td>
                        <td>{{ !empty($invoice->clients) ? $invoice->clients->full_name : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>
                            {{ !empty($invoice->client) && !empty($invoice->clients->clients) ? $invoice->clients->clients->service_address : '' }},
                            @if (!empty($invoice->clients) && !empty($invoice->clients->clients) && !empty($invoice->clients->clients->service_city))
                                {{ $invoice->clients->clients->service_city }},
                                {{ $invoice->clients->clients->service_country }},
                                {{ $invoice->clients->clients->service_zip_code }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>+{{ preg_replace('/[^0-9]/', '', $invoice->clients?->ccm) . ' ' . $invoice->clients?->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ !empty($invoice->clients) ? $invoice->clients->email : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>TRN No:</strong></td>
                        <td>{{ !empty($invoice->clients) ? $invoice->clients->gst : '' }}</td>
                    </tr>
                </table>
            </div>
            <div style="position:absolute;right:0;top:0;border:1px solid black;width:45%">
                <table class="custom-table align-first-column-right">
                    <tr>
                        <td><strong>Registration No:</strong></td>
                        <td>{{ $vehicle->rego }}</td>
                    </tr>
                    <tr>
                        <td><strong>Make:</strong></td>
                        <td>{{ $vehicle->vehicle_makes ? $vehicle->vehicle_makes->make_name : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Model:</strong></td>
                        <td>{{ $vehicle->vehicle_models ? $vehicle->vehicle_models->model_name : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Year:</strong></td>
                        <td>{{ $vehicle->vehicle_models ? $vehicle->model_series : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Technician:</strong></td>
                        <td>{{ $technician->full_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Method:</strong></td>
                        <td>{{ $payment->payment_method ?? '' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Product Table -->
        <table class="custom-table" style="margin-bottom:2%;">
            <thead>
                <tr>
                    <th>SL</th>
                    <th style="text-align:left">Description</th>
                    <th>QTY</th>
                    <th>Unit Price</th>
                    <th>Sub Total</th>
                    <th>VAT</th>
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
                            <td class="center">{{ $key + 1 }}</td>
                            <td>
                                <strong>{{ $item->product_name }}</strong><br>
                                @if ($item->warranty_exists)
                                    <strong>Warranty:</strong> {{ $item->warranty_number }}<br>
                                    <span>Warranty Period:
                                        {{ \Carbon\Carbon::parse($item->from_date)->format('d M Y') }}
                                        to {{ \Carbon\Carbon::parse($item->to_date)->format('d M Y') }}
                                        ({{ \Carbon\Carbon::parse($item->to_date)->diffInMonths(\Carbon\Carbon::parse($item->from_date)) }}
                                        Months)
                                    </span>
                                @endif
                            </td>
                            <td class="center">{{ $item->qty }}</td>
                            <td class="center">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="center">{{ number_format($item->linetotal, 2) }}</td>
                            <td class="center">{{ number_format($item->taxamount, 2) }}</td>
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
            <tbody>
                <tr class="totals">
                    <td colspan="3"></td>
                    <td colspan="2">Subtotal Excl. VAT</td>
                    <td class="right">{{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3"></td>
                    <td colspan="2">Discount</td>
                    <td class="right">{{ number_format($discount, 2) }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3"></td>
                    <td colspan="2">VAT</td>
                    <td class="right">{{ number_format($tax, 2) }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3" style="text-align:left;"></td>
                    <td colspan="2"><strong>Total (AED)</strong></td>
                    <td class="right"><strong>{{ number_format($totalamount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Terms -->
        <div class="mb-1">
            <strong>Terms and Conditions:</strong>
            <ol>
                <li>Any discrepancies in Invoice or goods received should be highlighted in writing to "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" within 5 working days of receipt of invoice or Delivery Note, otherwise the Invoice will be treated as valid and payable in full.</li>
                <li>Goods cannot be returned once sold unless proven of manufacturing defect on proper inspection by both "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" and its "CUSTOMER".</li>
                <li>The primary responsibility of warranty services, if any, rests with the principal supplier or manufacturer.</li>
                <li>The Payment must be settled on the due date as per the Terms stipulated in the Invoices Submitted by "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC".</li>
                <li>In the case of any breach on the agreed payment terms, "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" has the right to suspend all supplies without prior notice and "DELTA TURF CARE AGRICULTURAL TRACTORS & MACHINERY TRADING LLC" has the right to recover its outstanding in full on first demand. Additionally, the buyer shall indemnify "DELTA TURF CARE AGRICULTURAL </li>
                <li>TRACTORS & MACHINERY TRADING LLC" for any Financial Damages suffered due to late payment, including but not limited to, attorney's fees and disbursements incurred to collect the overdue amount.</li>
            </ol>
            <!-- <p class="footer-note fw-bold">*I hereby confirm receipt of the goods in good condition and agree to dispose
                of the old battery in compliance with UAE regulations through authorized channels, ensuring proper
                handling and recycling.</p> -->
        </div>

        <!-- Signatures -->
        <table class="custom-table" style="width: 100%;">
            <tr>
                <td style="width: 50%; padding-left: 2rem !important; vertical-align: top;">
                    <p><strong>Prepared By</strong></p>
                    <p>
                        {{ $workorder->created_by && $workorder->agent->full_name
                            ? $workorder->agent->full_name
                            : auth()->user()->full_name }}
                    </p>
                </td>
                <td style="width: 50%; padding-left: 2rem !important; vertical-align: top;">
                    <p><strong>Customer</strong></p>
                    <p>{{ !empty($invoice->clients) ? $invoice->clients->full_name : '' }}</p>
                </td>
            </tr>
        </table>

        <p class="footer-note text-center mt-2">** This is a computer-generated invoice and does not require a signature
            **</p>

        <br>
        <hr style="height:5px;background:yellow !important;margin:0;">
        <hr style="height:5px;background:blue !important;margin:0;">
        <br>
        <div class="text-center text-sm">
            <strong style="font-size: 20px !important;">Delta Turf Care</strong>
            <br>
             Agricultural Tractors & Machinery Trading LLC
        </div>
        <div class="text-center text-sm">
             
            <p>P.O.Box: Nabra At | Almasani' | Riyadh</p>
            <p>info@deltaturfcare.com | www.deltaturfcare.com | Facebook.com/deltaturf | +996 114 333 458
            </p>
        </div>
    </div>
</body>

</html>
