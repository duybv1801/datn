<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Remote;
use Carbon\Carbon;


class NotifiProvider extends ServiceProvider
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
        View::composer('layouts.notifi', function ($view) {
            $remotes = Remote::where('status', 1)->get();
            $notifications = collect($remotes)->sortByDesc('created_at');
            $unreadNotifications = count($notifications);

            $view->with([
                'notifications' => $notifications,
                'unreadNotifications' => $unreadNotifications
            ]);
        });
    }
}
