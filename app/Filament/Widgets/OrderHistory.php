<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;

use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrderHistory extends LineChartWidget
{
    protected static ?string $heading = 'Riwayat Penjualan Produk Bulan Ini';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {

        $snackSurabaya = Trend::query(OrderItem::where('product_id', '2'))
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->sum('qty');

        $snackBanyuwangi = Trend::query(OrderItem::where('product_id', '3'))
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->sum('qty');

        return [
            'datasets' => [
                [
                    'label' => 'Paket Snack Surabaya',
                    'data' => $snackSurabaya->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(154, 220, 255)',
                ],
                [
                    'label' => 'Paket Snack Banyuwangi',
                    'data' => $snackBanyuwangi->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(255, 138, 174)',
                ],
            ],
            'labels' => $snackSurabaya->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
