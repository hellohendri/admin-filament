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
        $orderNumber = 'OR-' . random_int(100000, 999999);
        $getUser = auth()->user()->name;
        $quantity = 0;
        $totalPrice = 0;

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('cashier')
                                    ->default($getUser)
                                    ->disabled(),
                                Forms\Components\TextInput::make('no_order')
                                    ->default($orderNumber)
                                    ->disabled(),
                                Forms\Components\BelongsToSelect::make('customer_name')
                                    ->relationship('customer_name_id', 'customer_name')
                                    ->label("Customer")
                                    ->placeholder('Pilih Customer')
                                    ->getOptionLabelUsing(fn ($value): ?string => Customer::find($value)?->name)
                                    ->required(),
                                Forms\Components\BelongsToSelect::make('payment_method')
                                    ->relationship('payment_method_id', 'payment_method')
                                    ->label('Metode Pembayaran')
                                    ->placeholder('Pilih Metode Pembayaran')
                                    ->getOptionLabelUsing(fn ($value): ?string => PaymentMethod::find($value)?->customer_name)
                                    ->required(),
                                Forms\Components\BelongsToSelect::make('payment_status')
                                    ->relationship('payment_status_id', 'payment_status')
                                    ->label('Status Pembayaran')
                                    ->placeholder('Pilih Status Pembayaran')
                                    ->getOptionLabelUsing(fn ($value): ?string => PaymentStatus::find($value)?->customer_name)
                                    ->required(),
                                Forms\Components\MarkdownEditor::make('notes')
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),
                            ])->columns([
                                'sm' => 2,
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Produk'),
                                Forms\Components\HasManyRepeater::make('items')
                                    ->relationship('items')
                                    ->schema([
                                        Forms\Components\Select::make('shop_product_id')
                                            ->label('Product')
                                            ->options(Product::query()->pluck('name', 'id'))
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                                            ->columnSpan([
                                                'md' => 5,
                                            ]),
                                    ])
                            ])
                    ])
            ])
            ->columns([
                'sm' => 2,
                'lg' => null,
            ]);
    }



















    // Card::make()
    //     ->schema([
    //         Forms\Components\TextInput::make('cashier')
    //             ->default($getUser)
    //             ->disabled(),

    //         Forms\Components\TextInput::make('no_order')
    //             ->default($orderNumber)
    //             ->disabled(),

    //         Forms\Components\Select::make('customer_name')
    //             ->label("Customer")
    //             ->placeholder('Pilih Customer')
    //             ->options(Customer::all()->pluck('customer_name', 'id')->toArray())
    //             ->required(),

    //         Forms\Components\Select::make('payment_method')
    //             ->label('Metode Pembayaran')
    //             ->placeholder('Pilih Metode Pembayaran')
    //             ->options(PaymentMethod::all()->pluck('payment_method', 'id')->toArray())
    //             ->required(),

    //         Forms\Components\Select::make('payment_status')
    //             ->label('Status Pembayaran')
    //             ->placeholder('Pilih Status Pembayaran')
    //             ->options(PaymentStatus::all()->pluck('payment_status', 'id')->toArray())
    //             ->required(),

    //         Forms\Components\DateTimePicker::make('date')
    //             ->label('Tanggal')
    //             ->withoutSeconds()
    //             ->default(date(now($tz = "Asia/Bangkok")))
    //             ->placeholder(date(now($tz = "Asia/Bangkok"))),

    //         Forms\Components\TextInput::make('quantity')
    //             ->numeric()
    //             ->default($quantity),

    //         Forms\Components\TextInput::make('total_price')
    //             ->numeric()
    //             ->default($totalPrice)
    //             ->columnSpan(2),
    //     ])
    //     ->columns(3),

    // Repeater::make("Produk")
    //     ->schema([
    //         Forms\Components\Select::make('product_name')
    //             ->label('Nama Produk')
    //             ->options(Product::where('outlet_name', 1)->pluck('name', 'id')->toArray())
    //             ->placeholder('Pilih Produk')
    //             ->required(),
    //         Forms\Components\TextInput::make('quantity')
    //             ->label('Jumlah')
    //             ->numeric()
    //             ->default(1),
    //         Forms\Components\TextInput::make('total_price')
    //             ->label('Jumlah')
    //             ->numeric()
    //             ->default(4900)
    //     ])
    //     ->columns(3)
    //     ->defaultItems(1)
    //     ->createItemButtonLabel('Tambah Produk'),
















    // Section::make('Konfigurasi Order')
    //     ->schema([
    //         Forms\Components\BelongsToSelect::make('cashier')
    //             ->label('Kasir')
    //             ->placeholder($getUser)
    //             ->default($getUser)
    //             ->relationship('cashier_id', 'name')
    //             ->disabled(),
    //         Forms\Components\TextInput::make('no_order')
    //             ->label('No. Order')
    //             ->placeholder($orderNumber)
    //             ->default($orderNumber)
    //             ->disabled(),
    //         Forms\Components\BelongsToSelect::make('customer_name')
    //             ->relationship('customer_name_id', 'name')
    //             ->label('Nama Customer')
    //             ->placeholder('Pilih Customer')
    //             ->default(Customer::where('id', 2)),
    //         Forms\Components\BelongsToSelect::make('payment_method')
    //             ->relationship('payment_method_id', 'payment_method')
    //             ->label('Metode Pembayaran')
    //             ->placeholder('Pilih Metode Pembayaran')
    //             ->default(PaymentMethod::where('id', 1)),
    //         Forms\Components\BelongsToSelect::make('payment_status')
    //             ->relationship('payment_status_id', 'payment_status')
    //             ->label('Status Pembayaran')
    //             ->placeholder('Pilih Status Pembayaran')
    //             ->default(PaymentStatus::where('id', 1)),
    //         Forms\Components\DateTimePicker::make('date')
    //             ->label('Tanggal')
    //             ->withoutSeconds()
    //             ->default(date(now($tz = "Asia/Bangkok")))
    //             ->placeholder(date(now($tz = "Asia/Bangkok"))),
    //     ])
    //     ->columns(3),

    // Section::make('Produk')
    //     ->schema([
    //         Repeater::make(' ')
    //             ->schema([
    //                 Forms\Components\Select::make('product_name')
    //                     ->label('Nama Produk')
    //                     ->options(Product::where('outlet_name', 1)->pluck('name', 'id')->toArray())
    //                     ->placeholder('Pilih Produk')
    //                     ->required()
    //                     ->reactive()
    //                     ->afterStateUpdated(fn (callable $set) => $set('total_price', null)),
    //                 Forms\Components\TextInput::make('quantity')
    //                     ->label('Jumlah')
    //                     ->numeric()
    //                     ->default(1)
    //                     ->required()
    //                     ->reactive()
    //                     ->afterStateUpdated(fn (callable $set) => $set('total_price', null)),
    //                 Forms\Components\TextInput::make('total_price')
    //                     ->label('Harga')
    //                     ->numeric()
    //                     ->placeholder(function (callable $get) {
    //                         $selectedProduct = Product::find($get('product_name'));
    //                         $quantity = $get('quantity');
    //                         $totalPrice = 0;

    //                         if (!$selectedProduct && !$quantity) {
    //                             return "Rp " . $totalPrice;
    //                         } elseif (!$selectedProduct) {
    //                             return "Rp " . $totalPrice;
    //                         }

    //                         $totalPrice += $selectedProduct->price * $quantity;

    //                         return "Rp " . number_format($totalPrice, 2, ',', '.');
    //                     })
    //                     ->default(function (callable $get) {
    //                         $selectedProduct = Product::find($get('product_name'));
    //                         $quantity = $get('quantity');
    //                         $totalPrice = 0;

    //                         if (!$selectedProduct && !$quantity) {
    //                             return $totalPrice;
    //                         } elseif (!$selectedProduct) {
    //                             return $totalPrice;
    //                         }

    //                         $totalPrice += $selectedProduct->price * $quantity;

    //                         return $totalPrice;
    //                     })
    //                     ->disabled(),
    //             ])
    //             ->columns(3)
    //             ->defaultItems(1)
    //             ->createItemButtonLabel('Tambah Produk'),
    //     ]),

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_order')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name_id.customer_name'),
                Tables\Columns\TextColumn::make('payment_method_id.payment_method'),
                Tables\Columns\BadgeColumn::make('payment_status_id.payment_status')
                    ->colors([
                        'secondary',
                        'danger' => 'Pending',
                        'warning' => 'Processing',
                        'success' => 'Confirmed',
                    ]),
                Tables\Columns\TextColumn::make('total_price'),
                Tables\Columns\TextColumn::make('date')
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
