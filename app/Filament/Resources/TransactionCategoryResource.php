<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionCategoryResource\Pages;
use App\Filament\Resources\TransactionCategoryResource\RelationManagers;
use App\Models\TransactionCategory;
use App\Models\TransactionType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class TransactionCategoryResource extends Resource
{
    protected static ?string $model = TransactionCategory::class;

    protected static ?string $navigationGroup = 'Master Management';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('transaction_category')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('transaction_category'),
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
            'index' => Pages\ListTransactionCategories::route('/'),
            'create' => Pages\CreateTransactionCategory::route('/create'),
            'edit' => Pages\EditTransactionCategory::route('/{record}/edit'),
        ];
    }
}
