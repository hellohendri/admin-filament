<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Product;
use Doctrine\DBAL\Query;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Card;

class OrderResource extends Resource
{

    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Orders & Transactions';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        date_default_timezone_set("Asia/Bangkok");
        $currentDate = date('M d, Y h:i');
        $orderNumber = 'OR-' . random_int(100000, 999999);
        $getUser = auth()->user()->name;
        $defaultCustomer = Customer::where('id', 2);

        return $form
            ->schema([
                Section::make('Konfigurasi Order')
                    ->schema([
                        Forms\Components\BelongsToSelect::make('cashier')
                            ->label('Kasir')
                            ->placeholder($getUser)
                            ->default($getUser)
                            ->relationship('cashier_id', 'name')
                            ->disabled(),
                        Forms\Components\TextInput::make('no_order')
                            ->label('No. Order')
                            ->placeholder($orderNumber)
                            ->default($orderNumber)
                            ->disabled(),
                        Forms\Components\BelongsToSelect::make('customer_name')
                            ->relationship('customer_name_id', 'name')
                            ->label('Nama Customer')
                            ->placeholder('Pilih Customer')
                            ->default(Customer::where('id', 2)),
                        Forms\Components\BelongsToSelect::make('payment_method')
                            ->relationship('payment_method_id', 'payment_method')
                            ->label('Metode Pembayaran')
                            ->placeholder('Pilih Metode Pembayaran')
                            ->default(PaymentMethod::where('id', 1)),
                        Forms\Components\BelongsToSelect::make('payment_status')
                            ->relationship('payment_status_id', 'payment_status')
                            ->label('Status Pembayaran')
                            ->placeholder('Pilih Status Pembayaran')
                            ->default(PaymentStatus::where('id', 1)),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Tanggal')
                            ->withoutSeconds()
                            ->default(date(now($tz = "Asia/Bangkok")))
                            ->placeholder(date(now($tz = "Asia/Bangkok"))),
                    ])
                    ->columns(3),

                Section::make('Produk')
                    ->schema([
                        Repeater::make(' ')
                            ->schema([
                                Forms\Components\Select::make('product_name')
                                    ->label('Nama Produk')
                                    ->options(Product::where('outlet_name', 1)->pluck('name', 'id')->toArray())
                                    ->placeholder('Pilih Produk')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('total_price', null)),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('total_price', null)),
                                Forms\Components\TextInput::make('total_price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->placeholder(function (callable $get) {
                                        $selectedProduct = Product::find($get('product_name'));
                                        $quantity = $get('quantity');
                                        $totalPrice = 0;

                                        if (!$selectedProduct && !$quantity) {
                                            return "Rp " . $totalPrice;
                                        } elseif (!$selectedProduct) {
                                            return "Rp " . $totalPrice;
                                        }

                                        $totalPrice += $selectedProduct->price * $quantity;

                                        return "Rp " . number_format($totalPrice, 2, ',', '.');
                                    })
                                    ->default(function (callable $get) {
                                        $selectedProduct = Product::find($get('product_name'));
                                        $quantity = $get('quantity');
                                        $totalPrice = 0;

                                        if (!$selectedProduct && !$quantity) {
                                            return $totalPrice;
                                        } elseif (!$selectedProduct) {
                                            return $totalPrice;
                                        }

                                        $totalPrice += $selectedProduct->price * $quantity;

                                        return $totalPrice;
                                    })
                                    ->disabled(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->createItemButtonLabel('Tambah Produk'),
                    ]),
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
