<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesReportResource\Pages;
use App\Filament\Resources\SalesReportResource\RelationManagers;
use App\Models\OrderDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Grouping\Group;


class SalesReportResource extends Resource
{
    protected static ?string $model = OrderDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Sales Report';

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
        ])
        ->defaultGroup(
            // Group rows by product_id
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
        ->filters([
            Tables\Filters\Filter::make('date_range')
                ->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date'),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    if ($data['start_date'] ?? null) {
                        $query->where('date', '>=', $data['start_date']);
                    }
                    if ($data['end_date'] ?? null) {
                        $query->where('date', '<=', $data['end_date']);
                    }
                    return $query;
                }),
        ])
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
}
