<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .company-details {
            color: #666;
            font-size: 14px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info, .customer-info {
            flex: 1;
        }
        .invoice-info h3, .customer-info h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 18px;
        }
        .info-row {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px;
            border: 1px solid #ddd;
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
            margin-left: auto;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row.final {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            margin-top: 10px;
            padding-top: 15px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        @media print {
            body {
                background-color: white;
            }
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
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
                    <div>Email: info@ecommerce.com</div>
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
                    <th>No</th>
                    <th>Product Name</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
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

        <!-- Payment Method -->
        <div style="margin-top: 30px;">
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

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This invoice was generated on {{ now()->format('d M Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
