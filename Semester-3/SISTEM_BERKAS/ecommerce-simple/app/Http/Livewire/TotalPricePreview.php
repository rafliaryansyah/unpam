<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class TotalPricePreview extends Component
{
    public $orderDetails = [];

    public function render()
    {
        $totalPrice = collect($this->orderDetails)->sum(function ($item) {
            $product = \App\Models\Product::find($item['product_id']);
            return $product ? $product->price * $item['quantity'] : 0;
        });
        return view('livewire.total-price-preview', ['totalPrice' => $totalPrice]);
    }

    protected function calculateTotalPrice()
    {
        return collect($this->orderDetails)->sum(function ($item) {
            $product = Product::find($item['product_id']);
            return $product ? $product->price * $item['quantity'] : 0;
        });
    }
}
