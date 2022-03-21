<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutletResource\Pages;
use App\Filament\Resources\OutletResource\RelationManagers;
use App\Models\Outlet;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class OutletResource extends Resource
{
    protected static ?string $model = Outlet::class;

    protected static ?string $navigationIcon = 'heroicon-o-library';

    protected static ?string $navigationGroup = 'Products & Outlets';

    protected static ?string $recordTitleAttribute = 'outlet_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('outlet_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('outlet_name'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('address'),
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
            'index' => Pages\ListOutlets::route('/'),
            'create' => Pages\CreateOutlet::route('/create'),
            'edit' => Pages\EditOutlet::route('/{record}/edit'),
        ];
    }
}
