<?php

namespace App\Filament\Widgets;

use App\Models\OrderDetail;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Sales Comparison (Current vs Previous Year)';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;
        
        $currentYearData = [];
        $previousYearData = [];
        $labels = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = now()->month($i)->format('M');
            
            // Current year data
            $currentSales = OrderDetail::whereHas('order', function ($q) use ($currentYear, $i) {
                $q->whereYear('created_at', $currentYear)
                  ->whereMonth('created_at', $i);
            })->sum('price');
            $currentYearData[] = $currentSales;
            
            // Previous year data
            $previousSales = OrderDetail::whereHas('order', function ($q) use ($previousYear, $i) {
                $q->whereYear('created_at', $previousYear)
                  ->whereMonth('created_at', $i);
            })->sum('price');
            $previousYearData[] = $previousSales;
        }

        return [
            'datasets' => [
                [
                    'label' => $currentYear . ' Sales',
                    'data' => $currentYearData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                ],
                [
                    'label' => $previousYear . ' Sales',
                    'data' => $previousYearData,
                    'borderColor' => 'rgb(156, 163, 175)',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
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
                        'label' => 'function(context) { return context.dataset.label + ": Rp " + context.parsed.y.toLocaleString(); }'
                    ]
                ]
            ]
        ];
    }
}
