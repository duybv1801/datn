<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Remote;

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

        View::composer('layouts.menu', function ($view) {
            $remotes = Remote::where('status', 1)->get();
            $user = Auth::user();
            $position = $user->position;
            $notifications = collect($remotes)->sortByDesc('created_at');
            $unreadNotifications = count($notifications);

            $view->with([
                'position' => $position,
                'notifications' => $notifications,
                'unreadNotifications' => $unreadNotifications
            ]);
        });
    }
}
