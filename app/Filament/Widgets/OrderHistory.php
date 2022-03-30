<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrderHistory extends LineChartWidget
{
    protected static ?string $heading = 'Riwayat Penjualan Paket Snack Abimanyu Travel';

    protected static ?int $sort = 2;

    // protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }

    protected function getData(): array
    {

        $activeFilter = $this->filter;

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

        $snackBromo = Trend::query(OrderItem::where('product_id', '4'))
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->sum('qty');

        return [
            'datasets' => [
                [
                    'label' => 'Snack Surabaya',
                    'data' => $snackSurabaya->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(154, 220, 255)',
                ],
                [
                    'label' => 'Snack Banyuwangi',
                    'data' => $snackBanyuwangi->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(255, 138, 174)',
                ],
                [
                    'label' => 'Snack Bromo',
                    'data' => $snackBromo->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(182, 255, 206)',
                ],
            ],
            'labels' => $snackSurabaya->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
