<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationGroup = 'Transactions';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function getWidgets(): array
    {
        return [
            Widgets\TransactionOverview::class,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\BelongsToSelect::make('payment_method')
                    ->relationship('payment_method_id', 'payment_method'),
                Forms\Components\BelongsToSelect::make('transaction_type')
                    ->relationship('transaction_type_id', 'transaction_type'),
                Forms\Components\BelongsToSelect::make('transaction_category')
                    ->relationship('transaction_category_id', 'transaction_category'),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
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
                // Tables\Columns\TextColumn::make('payment_method_id.payment_method'),
                Tables\Columns\TextColumn::make('transaction_type_id.transaction_type')
                    ->label("Tipe Transaksi"),
                // Tables\Columns\TextColumn::make('transaction_category_id.transaction_category'),
                Tables\Columns\TextColumn::make('total')
                    ->money('idr', true),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit('30'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
            ])
            ->defaultSort('date', 'desc')
            ->pushActions([
                Tables\Actions\LinkAction::make('delete')
                    ->action(fn (Transaction $record) => $record->delete())
                    ->requiresConfirmation()
                    ->color('danger'),
            ])
            ->filters([
                SelectFilter::make('transaction_type')
                    ->options([
                        '1' => 'Masuk',
                        '2' => 'Keluar',
                    ])
                    ->column('transaction_type'),
                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from'),
                        Forms\Components\DatePicker::make('date_until')->default(now()),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
