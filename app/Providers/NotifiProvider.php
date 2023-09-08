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
            $user = Auth::user();
            if ($user->position == config('database.position.po')) {
                $remotes = Remote::where('status',  config('database.remotes.pending'))
                    ->where('approver_id', $user->id)
                    ->get();
            } else {
                $remotes = Remote::where('status',  config('database.remotes.pending'))->get();
            }
            $notifications = collect($remotes)->sortByDesc('created_at');
            $unreadNotifications = count($notifications);

            $view->with([
                'notifications' => $notifications,
                'unreadNotifications' => $unreadNotifications
            ]);
        });

        View::composer('layouts.menu', function ($view) {
            $user = Auth::user();
            $remotes = Remote::where('status',  config('database.remotes.pending'))
                ->where('approver_id', $user->id)
                ->get();
            $position = $user->position;
            $notifications = collect($remotes);
            $unreadNotifications = count($notifications);

            $view->with([
                'position' => $position,
                'notifications' => $notifications,
                'unreadNotifications' => $unreadNotifications
            ]);
        });
        View::composer('layouts.menu', function ($view) {
            $user = Auth::user();
            $remotes = Remote::where('status',  config('database.remotes.pending'))
                ->where('user_id', $user->id)
                ->get();
            $notifications = collect($remotes);
            $register = count($notifications);

            $view->with([
                'register' => $register
            ]);
        });

        View::composer('remote.manager.approve', function ($view) {
            $user = Auth::user();
            $position = $user->position;

            $view->with([
                'position' => $position,
            ]);
        });
    }
}
