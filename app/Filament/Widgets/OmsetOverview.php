<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OmsetOverview extends BaseWidget
{

    public $totalOmset;
    public $totalOmsetConfirmed;
    public $totalOmsetPending;

    protected static ?string $heading = 'Coba Heading';

    protected function getCards(): array
    {

        $totalOmset = Order::all()->pluck('total_price')->sum();
        $totalOmsetConfirmed = Order::where('payment_status', 1)->pluck('total_price')->sum();
        $totalOmsetPending = Order::where('payment_status', 2)->pluck('total_price')->sum();

        return [
            Card::make('Omset Penjualan', "Rp " . number_format($totalOmset, 2, ',', '.'))
                ->description('Total Omset Penjualan')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('primary'),
            Card::make('Omset Penjualan Terkonfirmasi', "Rp " . number_format($totalOmsetConfirmed, 2, ',', '.'))
                ->description('Total Omset Penjualan Terkonfirmasi')
                ->descriptionIcon('heroicon-s-badge-check')
                ->color('success'),
            Card::make('Omset Penjualan Pending', "Rp " . number_format($totalOmsetPending, 2, ',', '.'))
                ->description('Total Omset Penjualan Pending')
                ->descriptionIcon('heroicon-s-minus-circle')
                ->color('danger'),
        ];
    }
}
