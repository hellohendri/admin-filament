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
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationGroup = 'Orders & Transactions';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function getWidgets(): array
    {
        return [
            Widgets\TransactionOverview::class,
        ];
    }

    public static function form(Form $form): Form
    {
        $currentDate = date('M d, Y');

        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->placeholder(date(now($tz = "Asia/Bangkok")))
                            ->default(date(now($tz = "Asia/Bangkok")))
                            ->label('Tanggal'),
                        Forms\Components\BelongsToSelect::make('payment_method')
                            ->relationship('payment_method_id', 'payment_method')
                            ->label('Metode Pembayaran')
                            ->placeholder('Pilih Metode Pembayaran')
                            ->required(),
                        Forms\Components\BelongsToSelect::make('transaction_type')
                            ->relationship('transaction_type_id', 'transaction_type')
                            ->label('Tipe Transaksi')
                            ->placeholder('Pilih Tipe Transaksi')
                            ->required(),
                        Forms\Components\BelongsToSelect::make('transaction_category')
                            ->relationship('transaction_category_id', 'transaction_category')
                            ->label('Kategori Transaksi')
                            ->placeholder('Pilih Kategori Transaksi')
                            ->required(),
                        Forms\Components\TextInput::make('total')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->label('Deskripsi')
                            ->helperText('Tuliskan detail transaksi disini (Contoh: Peluanasan Invoice PT ATP 09/03/2022).'),
                    ])
                    ->columns(2)
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
                Tables\Columns\TextColumn::make('transaction_type_id.transaction_type')
                    ->label("Tipe Transaksi"),
                Tables\Columns\TextColumn::make('total')
                    ->money('idr', true),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit('30')
                    ->searchable(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
