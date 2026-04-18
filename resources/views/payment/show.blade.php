<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 30px;
            background: #f9f9f9;
        }

        .invoice-box {

            max-width: 800px;
            height: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 16px rgba(0, 0, 0, .1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: %100;
        }

        .header .logo {
            max-width: 20%;
        }

        .company-details {
            text-align: right;
        }

        .company-name {
            font-size: 26px;
            font-weight: bold;
        }

        .company-address {
            font-size: 14px;
            color: #555;
        }

        .hr {
            border: none;
            border-bottom: 1px solid #ccc;
            margin: 20px 0;
        }

        .section {
            text-align: center;
            margin: 30px 0;
        }

        .section h2 {
            font-size: 22px;
            font-weight: bold;
            text-decoration: underline;
        }

        .customer-details {
            text-align: center;
            font-size: 15px;
            line-height: 1.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0 10px;
            ;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background: #f2f2f2;
        }

        .summary {
            float: right;
            font-size: 15px;
            margin: 0px 0 30px;
        }

        .summary div {
            display: table-row-group;
            justify-content: space-between;
            width: 300px;
            padding: 5px 0;
        }

        .summary strong {
            text-align: right;
            flex: 1;
            margin-right: 10px;
        }

        .summary span {
            text-align: left;
            flex: 1;
        }

        .notes {
            font-size: 14px;
            color: #777;
            clear: both;
        }

        .footer {
            font-size: 16px;
            text-align: center;
            margin-top: 130px;
            margin-bottom: 10px;
            color: #888;
            clear: both;
            /* if you have floated elements above */
        }

        /* @media (min-width:320px){
            table{
                width:600px
            }
        } */
    </style>
</head>

<body>

    <div class="invoice-box">

        <div class="header" style="margin-bottom:40px;">
            <div class="logo" style="float:left;">
                <img src=" {{ asset('assets/invoice.png') }}" alt="Company Logo" width="170">
            </div>
            <div class="company-details" style="float:right;">
                <div><strong>Payment Date:</strong>{{ date('d/m/Y', strtotime($payments->payment_date)) }}</div>
                <div><strong>Payment Receipt No:</strong>{{ $payments->id }}</div>
            </div>
        </div>

       <div style="text-align:center;margin:20px 0;">
            <div class="company-name">{{ $settings['company_name'] }}</div>
            <div class="company-address">
                Address: {{ $settings['company_address'] }}<br />
                Mobile: {{ $settings['company_phone'] }} | Email: {{ $settings['company_email'] }} | Website:
                {{ $settings['company_website'] }}
            </div>
        </div>
        <div class="hr"></div>

        <div class="section">
            <h2>Receipt</h2>
        </div>

        <div class="customer-details">
            <div><strong>Customer Name:</strong>{{ $payments->user?->fullname }}</div>
            <div><strong>Address:</strong> --</div>
            <div>
                <strong>Phone: </strong>
                +{{ preg_replace('/[^0-9]/', '', $payments->user?->ccm) . $payments->user?->phone_number }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Tax Invoice No</th>
                    <th>Tax Invoice Date</th>
                    <th>Workorder</th>
                    <th>Received Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> {{ date('d/m/Y', strtotime($payments->payment_date)) }}</td>
                    <td>{{ $payments->payment_method }}</td>
                    <td>{{ $payments->invoice }}</td>
                    <td>{{ $payments->invoices?->invoice_date }}</td>
                    <td>#WO-{{ $payments->workorder }}</td>
                    <td>{{ number_format((float) array_sum(explode(',', $payments->paid_amount)), 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="summary">
            <!-- <div><strong>Invoice Amount:</strong>
                <span>{{ $invoice !== null ? number_format((float) $invoice->final_amount, 2) : '0.00' }}</span>
            </div> -->
            <!-- <div><strong>Received Amount:</strong> <span>{{ number_format((float) $payments->paid_amount ?? 0, 2) }}</span>
            </div> -->
            <!-- <div><strong>Balance Amount:</strong>
                <span>{{ $invoice !== null ? number_format((float) $invoice?->final_amount - (float) $payments->paid_amount, 2) : '0.00' }}</span>
            </div> -->
        </div>
        <strong style="float: left; padding-top:40px;">
            Collection Agent:- {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
        </strong>

        <div class="notes">
            <br>
            <strong>Note:</strong>
            <ol>
                <li>This is a digital receipt and does not require a signature.</li>
                <li>For a soft copy with signature, please contact our billing department at <a
                        href="mailto:{{ $settings['company_email'] }}">info@deltaturfcare.com</a>.</li>
            </ol>
        </div>

        <div class="footer">
            Thank you for your payment
        </div>
    </div>

</body>

</html>
