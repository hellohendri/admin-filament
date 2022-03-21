<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Doctrine\DBAL\Query;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class OrderResource extends Resource
{

    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Orders & Transactions';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {

        $orderNumber = 'OR-' . random_int(100000, 999999);
        $getUser = auth()->user()->id;

        return $form
            ->schema([
                // Forms\Components\TextInput::make('cashier')
                //     ->maxLength(255),
                Forms\Components\BelongsToSelect::make('cashier')
                    ->default($getUser)
                    ->relationship('cashier_id', 'name')
                    ->disabled()
                    ->label('Cashier'),
                Forms\Components\TextInput::make('no_order')
                    ->default($orderNumber)
                    ->required()
                    ->disabled()
                    ->label('No. Order'),
                Forms\Components\BelongsToSelect::make('customer_name')
                    ->relationship('customer_name_id', 'name')
                    ->label('Nama Customer')
                    ->placeholder('Pilih Customer'),
                Forms\Components\BelongsToSelect::make('payment_method')
                    ->relationship('payment_method_id', 'payment_method')
                    ->label('Metode Pembayaran')
                    ->placeholder('Pilih Metode Pembayaran'),
                Forms\Components\BelongsToSelect::make('payment_status')
                    ->relationship('payment_status_id', 'payment_status')
                    ->label('Status Pembayaran')
                    ->placeholder('Pilih Status Pemabayaran'),
                Forms\Components\BelongsToSelect::make('product_name')
                    ->relationship('product_name_id', 'name')
                    ->label('Nama Produk')
                    ->placeholder('Pilih Produk'),
                Forms\Components\TextInput::make('quantity')
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cashier'),
                Tables\Columns\TextColumn::make('no_order'),
                Tables\Columns\TextColumn::make('customer_name'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('product_name'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('total_price'),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
