<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Widgets\OrderStatusOverview;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Product;
use Doctrine\DBAL\Query;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Card;

class OrderResource extends Resource
{

    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Penjualan';

    protected static ?string $navigationGroup = 'Penjualan & Transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        $orderNumber = 'OR-' . random_int(100000, 999999);
        $getUser = auth()->user()->name;

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
                                Forms\Components\DateTimePicker::make('date')
                                    ->placeholder(date(now($tz = "Asia/Jakarta")))
                                    ->default(date(now($tz = "Asia/Jakarta")))
                                    ->label('Tanggal'),
                                Forms\Components\MarkdownEditor::make('notes')
                                    ->columnSpan([
                                        'sm' => 2,
                                    ]),
                            ])->columns([
                                'sm' => 2,
                            ]),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Items'),
                        Forms\Components\HasManyRepeater::make('items')
                            ->relationship('items')
                            ->schema([
                                // Hidden DateTimePicker (get current date time for chart filtering)
                                Forms\Components\DateTimePicker::make('date')
                                    ->placeholder(date(now($tz = "Asia/Jakarta")))
                                    ->default(date(now($tz = "Asia/Jakarta")))
                                    ->label('Tanggal')
                                    ->columnSpan([
                                        'md' => 2,
                                    ]),
                                // End of hidden date
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->options(Product::where('outlet_name', 1)->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),
                                Forms\Components\TextInput::make('qty')
                                    ->numeric()
                                    ->mask(
                                        fn (Forms\Components\TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->integer()
                                    )
                                    ->default(1)
                                    ->columnSpan([
                                        'md' => 2,
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Harga Produk')
                                    ->disabled()
                                    ->numeric()
                                    ->required()
                                    ->columnSpan([
                                        'md' => 3,
                                    ]),
                            ])
                            ->createItemButtonLabel('Tambah Item')
                            ->dehydrated()
                            ->defaultItems(1)
                            ->disableLabel()
                            ->columns([
                                'md' => 10,
                            ])
                            ->required(),
                    ])->columnSpan(1)
            ])
            ->columns([
                'sm' => 2,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_order')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name_id.customer_name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method_id.payment_method')
                    ->label('Metode Pembayaran'),
                Tables\Columns\BadgeColumn::make('payment_status_id.payment_status')
                    ->label('Status Pembayaran')
                    ->colors([
                        'secondary',
                        'danger' => 'Pending',
                        'warning' => 'Processing',
                        'success' => 'Confirmed',
                    ]),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('idr', true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('payment_status')
                    ->options([
                        '1' => 'Confirmed',
                        '2' => 'Pending',
                    ])
                    ->column('payment_status'),
                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from'),
                        Forms\Components\DatePicker::make('date_until')->default(now($tz = "Asia/Jakarta")),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
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
