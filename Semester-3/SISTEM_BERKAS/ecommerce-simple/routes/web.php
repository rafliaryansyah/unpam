<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invoice/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
Route::get('/invoice/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoice.pdf');
Route::get('/invoice/{invoice}/download', [InvoiceController::class, 'download'])->name('invoice.download');
