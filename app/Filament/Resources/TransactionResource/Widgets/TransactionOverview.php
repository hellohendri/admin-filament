<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;

use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TransactionOverview extends BaseWidget
{

    protected function getCards(): array
    {

        $currentMonth = date('m');

        $income = Transaction::where('transaction_type', 1)
            ->whereRaw('MONTH(date) = ' . $currentMonth)
            ->sum('total');

        $expense = Transaction::where('transaction_type', 2)
            ->whereRaw('MONTH(date) = ' . $currentMonth)
            ->sum('total');

        $balance = $income - $expense;

        $incomeWithCurrency = "Rp " . number_format($income, 2, ',', '.');
        $expenseWithCurrency = "Rp " . number_format($expense, 2, ',', '.');
        $balanceWithCurrency = "Rp " . number_format($balance, 2, ',', '.');

        return [
            Card::make('Total Pemasukan', $incomeWithCurrency)
                ->description('Total Pemasukan Dari Semua Jenis Transaksi' . $currentMonth)
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),
            Card::make('Total Pengeluaran', $expenseWithCurrency)
                ->description('Total Pengeluaran Dari Semua Jenis Transaksi')
                ->descriptionIcon('heroicon-s-trending-down')
                ->color('danger'),
            Card::make('Saldo', $balanceWithCurrency)
                ->description('Jumlah Saldo Saat Ini')
                ->descriptionIcon('heroicon-s-credit-card')
                ->color('warning'),
        ];
    }
}
