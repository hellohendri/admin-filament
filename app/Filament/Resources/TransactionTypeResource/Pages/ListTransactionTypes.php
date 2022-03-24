<?php

namespace App\Filament\Resources\TransactionTypeResource\Pages;

use App\Filament\Resources\TransactionTypeResource;
use Filament\Resources\Pages\ListRecords;

class ListTransactionTypes extends ListRecords
{
    protected static string $resource = TransactionTypeResource::class;

    protected static ?string $title = 'Tipe Transaksi';
}
