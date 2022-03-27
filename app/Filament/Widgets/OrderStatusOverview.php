<?php

namespace App\Filament\Widgets;

use App\Models\Order;

use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrderStatusOverview extends BaseWidget
{

    public $totalOrder;
    public $orderConfirmed;
    public $orderPending;

    protected function getCards(): array
    {
        $currentMonth = date('m');

        $totalOrder = Order::all()
            ->count();

        $orderConfirmed = Order::where('payment_status', 1)
            ->count();

        $orderPending = Order::where('payment_status', 2)
            ->count();

        return [
            Card::make('Total Penjualan', $totalOrder)
                ->description('Total penjualan bulan ini')
                ->descriptionIcon('heroicon-s-shopping-cart')
                ->color('primary'),
            Card::make('Penjualan Terkonfirmasi', $orderConfirmed)
                ->description('Total penjualan terkonfirmasi bulan ini')
                ->descriptionIcon('heroicon-s-badge-check')
                ->color('success'),
            Card::make('Penjualan Pending', $orderPending)
                ->description('Total penjualan pending bulan ini')
                ->descriptionIcon('heroicon-s-minus-circle')
                ->color('danger'),
        ];
    }
}
