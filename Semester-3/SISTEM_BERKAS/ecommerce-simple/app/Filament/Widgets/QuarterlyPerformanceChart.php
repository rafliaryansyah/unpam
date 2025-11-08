<?php

namespace App\Filament\Widgets;

use App\Models\OrderDetail;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class QuarterlyPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Quarterly Performance';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $currentYear = now()->year;
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $labels = [];
        $data = [];

        foreach ($quarters as $index => $quarter) {
            $startMonth = ($index * 3) + 1;
            $endMonth = ($index + 1) * 3;
            
            $labels[] = $quarter . ' ' . $currentYear;
            
            $quarterlySales = OrderDetail::whereHas('order', function ($q) use ($currentYear, $startMonth, $endMonth) {
                $q->whereYear('created_at', $currentYear)
                  ->whereBetween(DB::raw('MONTH(created_at)'), [$startMonth, $endMonth]);
            })->sum('price');
            
            $data[] = $quarterlySales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Quarterly Sales (Rp)',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                    ],
                    'borderColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
