<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Customer;
use App\Models\Order;
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
                            ->placeholder($getUser)
                            ->default($getUser)
                            ->relationship('cashier_id', 'name')
                            ->disabled()
                            ->label('Cashier'),
                        Forms\Components\TextInput::make('no_order')
                            ->placeholder($orderNumber)
                            ->default($orderNumber)
                            ->required()
                            ->disabled()
                            ->label('No. Order'),
                        Forms\Components\BelongsToSelect::make('customer_name')
                            ->relationship('customer_name_id', 'name')
                            ->label('Nama Customer')
                            ->placeholder('Pilih Customer')
                            ->default($defaultCustomer),
                        Forms\Components\BelongsToSelect::make('payment_method')
                            ->relationship('payment_method_id', 'payment_method')
                            ->label('Metode Pembayaran')
                            ->placeholder('Pilih Metode Pembayaran'),
                        Forms\Components\BelongsToSelect::make('payment_status')
                            ->relationship('payment_status_id', 'payment_status')
                            ->label('Status Pembayaran')
                            ->placeholder('Pilih Status Pembayaran'),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Tanggal')
                            ->withoutSeconds()
                            ->default(date(now($tz = "Asia/Bangkok")))
                            ->placeholder(date(now($tz = "Asia/Bangkok")))
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('Produk')
                    ->schema([
                        Repeater::make(' ')
                            ->schema([
                                Forms\Components\Select::make('product_name')
                                    // ->relationship('product_name_id', 'name')
                                    ->label('Nama Produk')
                                    ->options(Product::where('outlet_name', 1)->pluck('name', 'id')->toArray())
                                    ->placeholder('Pilih Produk')
                                    ->disablePlaceholderSelection()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (collable $set) => $set('total_price', null)),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->default(1)
                                    ->required(),
                                Forms\Components\Select::make('total_price')
                                    ->label('Harga')
                                    ->options(Product::all()
                                        ->pluck('price', 'id'))
                                    ->placeholder(function (collable $get) {
                                        $selectedProduct = Product::find($get('product_name'));
                                        if (!$selectedProduct) {
                                            return Product::all()->pluck('price', 'id');
                                        }
                                        return $selectedProduct->price->pluck('price', 'id');
                                    })
                                    ->default('4900')
                                    ->disabled()
                                    ->required(),
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
