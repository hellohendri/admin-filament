<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected static ?string $title = 'Transaksi';

    protected function getHeaderWidgets(): array
    {
        return [
            TransactionResource\Widgets\TransactionOverview::class,
        ];
    }
}
