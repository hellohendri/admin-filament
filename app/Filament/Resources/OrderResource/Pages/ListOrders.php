<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected static ?string $title = 'Penjualan';

    protected function getHeaderWidgets(): array
    {
        return [
            OrderResource\Widgets\OrderStatusOverview::class,
        ];
    }
}
