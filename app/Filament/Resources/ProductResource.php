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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Products & Outlets';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\BelongsToSelect::make('product_category')
                    ->required()
                    ->relationship('product_category_id', 'product_category'),
                Forms\Components\BelongsToSelect::make('outlet')
                    ->relationship('outlet_name_id', 'outlet_name'),
                Forms\Components\TextInput::make('stocks')
                    ->required(),
                Forms\Components\TextInput::make('cogs')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('production_date')
                    ->required(),
                Forms\Components\DatePicker::make('expired_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('product_category_id.product_category'),
                Tables\Columns\TextColumn::make('outlet_name_id.outlet_name'),
                Tables\Columns\TextColumn::make('stocks'),
                Tables\Columns\TextColumn::make('cogs'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('production_date')
                    ->date(),
                Tables\Columns\TextColumn::make('expired_date')
                    ->date(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
