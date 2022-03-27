<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class StrudelSalesChart extends LineChartWidget
{
    protected static ?string $heading = 'Riwayat Penjualan Strudel';

    protected static ?int $sort = 3;

    protected function getData(): array
    {

        $strudelPisangCoklat = Trend::query(OrderItem::where('product_id', '5'))
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->sum('qty');

        return [
            'datasets' => [
                [
                    'label' => 'Pisang Coklat',
                    'data' => $strudelPisangCoklat->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(154, 220, 255)',
                ],
            ],
            'labels' => $strudelPisangCoklat->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
