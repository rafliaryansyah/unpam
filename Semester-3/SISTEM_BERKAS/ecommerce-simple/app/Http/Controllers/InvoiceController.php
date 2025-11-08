<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function print(Invoice $invoice)
    {
        $invoice->load(['order.orderDetails.product', 'user', 'membership', 'order.paymentMethod']);
        
        return view('invoices.print', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['order.orderDetails.product', 'user', 'membership', 'order.paymentMethod']);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
        
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function download(Invoice $invoice)
    {
        $invoice->load(['order.orderDetails.product', 'user', 'membership', 'order.paymentMethod']);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
