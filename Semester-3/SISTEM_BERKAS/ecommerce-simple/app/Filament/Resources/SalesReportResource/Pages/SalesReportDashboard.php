<?php

namespace App\Filament\Resources\SalesReportResource\Pages;

use App\Filament\Resources\SalesReportResource;
use App\Models\OrderDetail;
use App\Models\Order;
use Filament\Resources\Pages\Page;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesReportDashboard extends Page
{
    protected static string $resource = SalesReportResource::class;

    protected static string $view = 'filament.resources.sales-report-resource.pages.sales-report-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            SalesStatsWidget::class,
            SalesChartWidget::class,
        ];
    }
}

class SalesStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = now();
        
        // Current month stats
        $currentMonthSales = OrderDetail::whereHas('order', function ($q) use ($now) {
            $q->whereYear('created_at', $now->year)
              ->whereMonth('created_at', $now->month);
        })->sum('price');

        $currentMonthOrders = Order::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        // Previous month for comparison
        $previousMonth = $now->copy()->subMonth();
        $previousMonthSales = OrderDetail::whereHas('order', function ($q) use ($previousMonth) {
            $q->whereYear('created_at', $previousMonth->year)
              ->whereMonth('created_at', $previousMonth->month);
        })->sum('price');

        $salesGrowth = $previousMonthSales > 0 
            ? (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100 
            : 0;

        return [
            StatsOverviewWidget\Stat::make('Total Sales (Current Month)', 'Rp ' . number_format($currentMonthSales, 0, ',', '.'))
                ->description($salesGrowth >= 0 ? '+' . number_format($salesGrowth, 1) . '% from last month' : number_format($salesGrowth, 1) . '% from last month')
                ->descriptionIcon($salesGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($salesGrowth >= 0 ? 'success' : 'danger'),

            StatsOverviewWidget\Stat::make('Total Orders (Current Month)', number_format($currentMonthOrders))
                ->description('Orders this month')
                ->descriptionIcon('heroicon-m-shopping-cart'),

            StatsOverviewWidget\Stat::make('Average Order Value', 'Rp ' . number_format($currentMonthOrders > 0 ? $currentMonthSales / $currentMonthOrders : 0, 0, ',', '.'))
                ->description('Per order this month')
                ->descriptionIcon('heroicon-m-currency-dollar'),
        ];
    }
}

class SalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Sales Trend (Last 6 Months)';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
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
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
