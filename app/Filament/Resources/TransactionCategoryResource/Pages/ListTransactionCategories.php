<?php

namespace App\Filament\Resources\TransactionCategoryResource\Pages;

use App\Filament\Resources\TransactionCategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListTransactionCategories extends ListRecords
{
    protected static string $resource = TransactionCategoryResource::class;

    protected static ?string $title = 'Kategori Transaksi';
}
