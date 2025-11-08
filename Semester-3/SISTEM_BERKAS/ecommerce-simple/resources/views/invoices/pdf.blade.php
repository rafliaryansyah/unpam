<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }
        
        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 15mm;
            background: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10mm;
            margin-bottom: 15mm;
        }
        
        .company-info {
            margin-bottom: 5mm;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3mm;
        }
        
        .company-details {
            color: #666;
            font-size: 11px;
        }
        
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 10mm;
        }
        
        .invoice-info, .customer-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 5mm;
        }
        
        .customer-info {
            padding-right: 0;
            padding-left: 5mm;
        }
        
        .invoice-info h3, .customer-info h3 {
            margin: 0 0 3mm 0;
            color: #333;
            font-size: 14px;
            font-weight: bold;
        }
        
        .info-row {
            margin-bottom: 1mm;
            font-size: 11px;
        }
        
        .label {
            font-weight: bold;
            color: #555;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10mm;
            font-size: 10px;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            padding: 2mm;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 10px;
        }
        
        .items-table td {
            padding: 2mm;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals {
            float: right;
            width: 60mm;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 1mm 0;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        
        .total-row.final {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            margin-top: 2mm;
            padding-top: 2mm;
        }
        
        .footer {
            margin-top: 15mm;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #eee;
            padding-top: 5mm;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        @page {
            margin: 15mm;
            size: A4;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">POS Store</div>
                <div class="company-details">
                    <div>Jl. Contoh Alamat No. 123</div>
                    <div>Jakarta, Indonesia 12345</div>
                    <div>Phone: +62 123 456 7890</div>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="invoice-info">
                <h3>Invoice Details</h3>
                <div class="info-row">
                    <span class="label">Invoice Number:</span> {{ $invoice->invoice_number }}
                </div>
                <div class="info-row">
                    <span class="label">Invoice Date:</span> {{ $invoice->created_at->format('d M Y') }}
                </div>
                <div class="info-row">
                    <span class="label">Order ID:</span> #{{ $invoice->order->id }}
                </div>
                <div class="info-row">
                    <span class="label">Cashier:</span> {{ $invoice->user->name }}
                </div>
            </div>
            <div class="customer-info">
                <h3>Customer Details</h3>
                <div class="info-row">
                    <span class="label">Name:</span> {{ $invoice->membership->full_name }}
                </div>
                <div class="info-row">
                    <span class="label">Phone:</span> {{ $invoice->membership->phone_number }}
                </div>
                @if($invoice->membership->email)
                <div class="info-row">
                    <span class="label">Email:</span> {{ $invoice->membership->email }}
                </div>
                @endif
                <div class="info-row">
                    <span class="label">Address:</span> {{ $invoice->order->customer_address }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Product Name</th>
                    <th style="width: 15%;" class="text-center">Qty</th>
                    <th style="width: 20%;" class="text-right">Unit Price</th>
                    <th style="width: 20%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->order->orderDetails as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="clearfix">
            <div class="totals">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($invoice->tax_amount > 0)
                <div class="total-row">
                    <span>Tax ({{ $invoice->tax_amount }}%):</span>
                    <span>Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>Total:</span>
                    <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div style="margin-top: 10mm; clear: both;">
            <div class="info-row">
                <span class="label">Payment Method:</span> {{ $invoice->order->paymentMethod->name }}
            </div>
            <div class="info-row">
                <span class="label">Status:</span> 
                <span style="text-transform: capitalize; font-weight: bold; color: {{ $invoice->status === 'paid' ? 'green' : ($invoice->status === 'pending' ? 'orange' : 'red') }}">
                    {{ $invoice->status }}
                </span>
            </div>
        </div>
    </div>
</body>
</html>
