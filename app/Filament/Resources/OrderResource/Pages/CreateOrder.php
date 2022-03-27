<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected static ?string $title = 'Tambah Penjualan';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['unit_price'] * $item['qty'];
        }

        $data['total_price'] = $total;

        return $data;
    }
}
