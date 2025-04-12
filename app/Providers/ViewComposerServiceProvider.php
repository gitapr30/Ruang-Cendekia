<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $unreadCount = 0;

            if (Auth::check()) {
                $userId = Auth::id();
                $today = Carbon::today();
                $threeDaysLater = $today->copy()->addDays(3);

                $upcomingCount = Borrow::where('user_id', $userId)
                    ->where('status', 'dipinjam')
                    ->whereBetween('tanggal_kembali', [$today, $threeDaysLater])
                    ->count();

                $overdueCount = Borrow::where('user_id', $userId)
                    ->where('status', 'dipinjam')
                    ->whereDate('tanggal_kembali', '<', $today)
                    ->count();

                $unreadCount = $upcomingCount + $overdueCount;
            }

            $view->with('unreadCount', $unreadCount);
        });
    }
}
