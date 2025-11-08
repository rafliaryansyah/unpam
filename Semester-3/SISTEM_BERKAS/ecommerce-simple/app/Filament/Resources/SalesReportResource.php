<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesReportResource\Pages;
use App\Filament\Resources\SalesReportResource\RelationManagers;
use App\Models\OrderDetail;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\DB;


class SalesReportResource extends Resource
{
    protected static ?string $model = OrderDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Sales Report';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?string $title = 'Sales Report';

    protected static ?string $label = 'Sales Report';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('product.name')
        ->columns([
            Tables\Columns\TextColumn::make('product.name')
                ->label('Product Name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('quantity')
                ->label('Quantity')
                ->sortable()
                ->formatStateUsing(fn ($state) => number_format($state)),
            Tables\Columns\TextColumn::make('price')
                ->label('Total Price')
                ->sortable()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            Tables\Columns\TextColumn::make('order.created_at')
                ->label('Order Date')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('report_type')
                ->label('Report Type')
                ->options([
                    'monthly' => 'Monthly Report',
                    'quarterly' => 'Quarterly Report',
                    'semester' => 'Semester Report',
                    'yearly' => 'Year-over-Year (YoY) Report',
                ])
                ->default('monthly')
                ->query(function (Builder $query, array $data) {
                    $reportType = $data['value'] ?? 'monthly';
                    return static::applyReportTypeFilter($query, $reportType);
                }),
            
            Filter::make('date_range')
                ->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date'),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    if ($data['start_date'] ?? null) {
                        $query->whereHas('order', function ($q) use ($data) {
                            $q->where('created_at', '>=', $data['start_date']);
                        });
                    }
                    if ($data['end_date'] ?? null) {
                        $query->whereHas('order', function ($q) use ($data) {
                            $q->where('created_at', '<=', $data['end_date']);
                        });
                    }
                    return $query;
                }),
        ])
        ->defaultGroup(
            Group::make('product_id')
                ->getKeyFromRecordUsing(
                    fn(OrderDetail $record): string => (string) $record->product_id
                )
                ->getTitleFromRecordUsing(
                    fn(OrderDetail $record): string => $record->product->name ?? 'Unknown Product'
                )
                ->orderQueryUsing(
                    fn(Builder $query, string $direction) => $query->orderBy('product_id', $direction)
                )
        )
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesReports::route('/'),
            'dashboard' => Pages\SalesReportDashboard::route('/dashboard'),
        ];
    }

    public static function canCreate(): bool
    {
       return false;
    }

    public static function canEdit($record): bool
    {
       return false;
    }

    /**
     * Apply report type filter to query
     */
    public static function applyReportTypeFilter(Builder $query, string $reportType): Builder
    {
        $now = now();
        
        switch ($reportType) {
            case 'monthly':
                return $query->whereHas('order', function ($q) use ($now) {
                    $q->whereYear('created_at', $now->year)
                      ->whereMonth('created_at', $now->month);
                });
                
            case 'quarterly':
                $quarter = ceil($now->month / 3);
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $quarter * 3;
                
                return $query->whereHas('order', function ($q) use ($now, $startMonth, $endMonth) {
                    $q->whereYear('created_at', $now->year)
                      ->whereBetween(DB::raw('MONTH(created_at)'), [$startMonth, $endMonth]);
                });
                
            case 'semester':
                $semester = $now->month <= 6 ? 1 : 2;
                $startMonth = $semester == 1 ? 1 : 7;
                $endMonth = $semester == 1 ? 6 : 12;
                
                return $query->whereHas('order', function ($q) use ($now, $startMonth, $endMonth) {
                    $q->whereYear('created_at', $now->year)
                      ->whereBetween(DB::raw('MONTH(created_at)'), [$startMonth, $endMonth]);
                });
                
            case 'yearly':
                return $query->whereHas('order', function ($q) use ($now) {
                    $q->whereYear('created_at', $now->year);
                });
                
            default:
                return $query;
        }
    }

    /**
     * Get sales summary statistics
     */
    public static function getSalesSummary(string $reportType = 'monthly'): array
    {
        $query = OrderDetail::with(['order', 'product']);
        $query = static::applyReportTypeFilter($query, $reportType);
        
        $totalSales = $query->sum('price');
        $totalQuantity = $query->sum('quantity');
        $totalOrders = $query->distinct('order_id')->count('order_id');
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        return [
            'total_sales' => $totalSales,
            'total_quantity' => $totalQuantity,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
        ];
    }
}
