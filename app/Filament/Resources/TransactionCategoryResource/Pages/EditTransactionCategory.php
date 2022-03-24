<?php

namespace App\Filament\Resources\TransactionCategoryResource\Pages;

use App\Filament\Resources\TransactionCategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditTransactionCategory extends EditRecord
{
    protected static string $resource = TransactionCategoryResource::class;

    protected static ?string $title = 'Edit Kategori Transaksi';
}
