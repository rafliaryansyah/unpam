<?php

namespace App\Filament\Widgets;

use App\Models\OrderDetail;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Trend (Last 12 Months)';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $sales = OrderDetail::whereHas('order', function ($q) use ($date) {
                $q->whereYear('created_at', $date->year)
                  ->whereMonth('created_at', $date->month);
            })->sum('price');
            
            $data[] = $sales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales (Rp)',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString(); }'
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Sales: Rp " + context.parsed.y.toLocaleString(); }'
                    ]
                ]
            ]
        ];
    }
}
