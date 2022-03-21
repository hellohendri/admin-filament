<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentStatusResource\Pages;
use App\Filament\Resources\PaymentStatusResource\RelationManagers;
use App\Models\PaymentStatus;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class PaymentStatusResource extends Resource
{
    protected static ?string $model = PaymentStatus::class;

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $navigationIcon = 'heroicon-o-badge-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('payment_status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_status'),
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
            'index' => Pages\ListPaymentStatuses::route('/'),
            'create' => Pages\CreatePaymentStatus::route('/create'),
            'edit' => Pages\EditPaymentStatus::route('/{record}/edit'),
        ];
    }
}
