<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TransactionOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $income = Transaction::where('transaction_type', 1)
            ->sum('total');

        $expense = Transaction::where('transaction_type', 2)
            ->sum('total');

        $balance = $income - $expense;

        return [
            Card::make('Total Pemasukan', $income)
                ->description('Total Pemasukan Dari Semua Jenis Transaksi')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),
            Card::make('Total Pengeluaran', $income)
                ->description('Total Pengeluaran Dari Semua Jenis Transaksi')
                ->descriptionIcon('heroicon-s-trending-down')
                ->color('danger'),
            Card::make('Saldo', $balance)
                ->description('Jumlah Saldo Saat Ini')
                ->descriptionIcon('heroicon-s-credit-card')
                ->color('warning'),
        ];
    }
}
