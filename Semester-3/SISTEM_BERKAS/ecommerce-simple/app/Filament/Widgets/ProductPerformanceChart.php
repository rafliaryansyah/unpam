<?php

namespace App\Filament\Widgets;

use App\Models\OrderDetail;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProductPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Top Products by Sales';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $productSales = OrderDetail::with('product')
            ->select('product_id', DB::raw('SUM(price) as total_sales'))
            ->groupBy('product_id')
            ->orderBy('total_sales', 'desc')
            ->limit(5)
            ->get();

        $labels = [];
        $data = [];
        $colors = [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(153, 102, 255)',
        ];

        foreach ($productSales as $index => $product) {
            $labels[] = $product->product->name ?? 'Unknown Product';
            $data[] = $product->total_sales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales (Rp)',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.label + ": Rp " + context.parsed.toLocaleString(); }'
                    ]
                ]
            ]
        ];
    }
}
