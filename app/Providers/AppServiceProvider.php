<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                'Master Manajemen',
                'Produk & Outlet',
                'Penjualan & Transaksi',
            ]);
        });
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make()
                ->group($group)
                ->icon($icon)
                ->isActiveWhen($closure)
                ->label($label)
                ->badge($badge)
                ->sort($sort)
                ->url($url),
        ];
    }
}
