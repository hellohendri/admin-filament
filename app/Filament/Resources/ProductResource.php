<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Produk & Outlet';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),
                Forms\Components\BelongsToSelect::make('product_category')
                    ->label('Kategori Produk')
                    ->required()
                    ->relationship('product_category_id', 'product_category'),
                Forms\Components\BelongsToSelect::make('outlet_name')
                    ->label('Outlet')
                    ->relationship('outlet_name_id', 'outlet_name'),
                Forms\Components\TextInput::make('stocks')
                    ->label('Jumlah Stock')
                    ->required(),
                Forms\Components\TextInput::make('cogs')
                    ->label('HPP')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('production_date')
                    ->label('Tanggal Produksi')
                    ->required(),
                Forms\Components\DatePicker::make('expired_date')
                    ->label('Tanggal Expired')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_category_id.product_category')
                    ->label('Kategori Produk'),
                Tables\Columns\TextColumn::make('outlet_name_id.outlet_name')
                    ->label('Nama Outlet'),
                // Tables\Columns\TextColumn::make('stocks')
                //     ->label('Jumlah Stock'),
                Tables\Columns\TextColumn::make('cogs')
                    ->label('HPP')
                    ->money('idr', true),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('idr', true),
                // Tables\Columns\TextColumn::make('production_date')
                //     ->label('Tanggal Produksi')
                //     ->date(),
                // Tables\Columns\TextColumn::make('expired_date')
                //     ->label('Tanggal Expired')
                //     ->date(),
            ])
            ->defaultSort('name')
            ->pushActions([
                Tables\Actions\LinkAction::make('delete')
                    ->action(fn (Transaction $record) => $record->delete())
                    ->requiresConfirmation()
                    ->color('danger'),
            ])
            ->filters([
                SelectFilter::make('product_category')
                    ->options([
                        '1' => 'Strudel',
                        '2' => 'Snack Banyuwangi',
                        '3' => 'Snack Surabaya',
                        '4' => 'Snack Travel',
                        '5' => 'Strudel Mini',
                        '6' => 'Paket Custom'
                    ])
                    ->column('product_category')
                    ->searchable()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
