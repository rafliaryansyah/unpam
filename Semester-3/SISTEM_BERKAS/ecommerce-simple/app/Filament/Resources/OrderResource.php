<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Http\Livewire\TotalPricePreview;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Livewire;
use Filament\Forms\Components\Placeholder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationLabel = 'Order';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('customer_name')
                        ->label('Customer Name')
                        ->required(),

                    Forms\Components\Textarea::make('customer_address')
                        ->label('Customer Address')
                        ->required(),

                    Forms\Components\Select::make('payment_method_id')
                        ->label('Pembayaran')
                        ->options(PaymentMethod::all()->mapWithKeys(function ($product) {
                            return [$product->id => "{$product->name}"];
                        }))
                        ->required(),

                        Forms\Components\HasManyRepeater::make('orderDetails')
                            ->relationship('orderDetails')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::all()->mapWithKeys(function ($product) {
                                        return [$product->id => "{$product->name} - (Rp {$product->price})"];
                                    }))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $product = Product::find($state);
                                        $quantity = $get('quantity') ?? 1;
                                        $set('price', $product ? $product->price * $quantity : 0);
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $product = Product::find($get('product_id'));
                                        $set('price', $product ? $product->price * $state : 0);
                                    }),

                                Forms\Components\TextInput::make('price')
                                    ->label('Price per Item')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->required()
                            ->dehydrateStateUsing(function (array $state, callable $set, callable $get) {
                                $orderDetails = $get('orderDetails') ?? [];
                                    $total = collect($orderDetails)->sum(function ($item) use ($get) {
                                        $product = Product::find($item['product_id']);
                                        return $product ? $product->price * $item['quantity'] : 0;
                                    });
                                return collect($state)->map(function ($item) {
                                    $product = Product::find($item['product_id']);
                                    $price = $product ? $product->price * $item['quantity'] : 0;
                                    return array_merge($item, ['price' => $price, 'total_price' => $price]);
                                })->toArray();
                            })
                            ->addActionLabel('Add Product'),


                            Placeholder::make('total_price')
                            ->label('Total Price')
                            ->content(function (callable $get) {
                                $orderDetails = $get('orderDetails') ?? [];
                                $total = collect($orderDetails)->sum(function ($item) use ($get) {
                                    $product = Product::find($item['product_id']);
                                    return $product ? $product->price * $item['quantity'] : 0;
                                });
                                return 'Rp ' . number_format($total, 2);
                            })
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('total_price')
                    ->reactive()
                    ->label('')
                    ->extraAttributes(['style' => 'display:none;'])
                    ->afterStateUpdated(function (callable $set, callable $get) {
                        $orderDetails = $get('orderDetails') ?? [];
                        $total = collect($orderDetails)->sum(function ($item) use ($get) {
                            $product = Product::find($item['product_id']);
                            return $product ? $product->price * $item['quantity'] : 0;
                        });
                        $set('total_price', $total);
                    })
                    ->dehydrateStateUsing(function ($set, callable $get) {
                        $orderDetails = $get('orderDetails') ?? [];
                        return collect($orderDetails)->sum(function ($item) use ($get) {
                            $product = Product::find($item['product_id']);
                            return $product ? $product->price * $item['quantity'] : 0;
                        });
                    })
                ])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer Name'),

                Tables\Columns\TextColumn::make('customer_address')
                    ->label('Customer Address'),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            // Define additional relationships if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
