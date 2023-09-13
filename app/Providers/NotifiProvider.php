<?php

namespace App\Providers;

use App\Models\Overtime;
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
        $statusOT = [
            config('define.overtime.registered'),
            config('define.overtime.admin_approve')
        ];

        View::composer('layouts.notifi', function ($view) use ($statusOT) {
            $user = Auth::user();
            $notifications = [];
            $ots = [];

            if (Auth::user()->hasRole('po')) {
                $remotes = Remote::where('status', config('define.remotes.pending'))
                    ->where('approver_id', $user->id)
                    ->get();

                $ots = Overtime::where('status', config('define.overtime.registered'))
                    ->where('approver_id', $user->id)
                    ->get();
            } else {
                $remotes = Remote::where('status', config('define.remotes.pending'))->get();
                $ots = Overtime::whereIn('status', $statusOT)->get();
            }

            $notifications = collect($remotes)->concat($ots)->sortByDesc('created_at');
            $unreadNotifications = count($notifications);

            $view->with([
                'notifications' => $notifications,
                'unreadNotifications' => $unreadNotifications,
            ]);
        });


        View::composer('layouts.menu', function ($view) {
            $user = Auth::user();
            $remotes = Remote::where('status',  config('define.remotes.pending'))
                ->where('approver_id', $user->id)
                ->get();
            $notificationRemotes = collect($remotes);
            $unreadNotificationRemotes = count($notificationRemotes);

            $view->with([
                'notificationRemotes' => $notificationRemotes,
                'unreadNotificationRemotes' => $unreadNotificationRemotes
            ]);
        });
        View::composer('layouts.menu', function ($view) {
            $user = Auth::user();
            $remotes = Remote::where('status', config('define.remotes.pending'))
                ->where('user_id', $user->id)
                ->get();
            $notificationRemotes = collect($remotes);
            $registerRemotes = count($notificationRemotes);

            $view->with([
                'registerRemotes' => $registerRemotes
            ]);
        });
        //OT
        View::composer('layouts.menu', function ($view) use ($statusOT) {
            $user = Auth::user();
            $overtimes = Overtime::where('status',  config('define.overtime.registered'))
                ->where('user_id', $user->id)
                ->get();
            $countRegisterOT = collect($overtimes);
            $registerOT = count($countRegisterOT);
            if (Auth::user()->hasRole('po')) {
                $ots = Overtime::where('status', config('define.overtime.registered'))
                    ->where('approver_id', $user->id)
                    ->get();
            } else {
                $ots = Overtime::whereIn('status', $statusOT)->get();
            }
            $notificationOT = collect($ots);
            $unreadNotificationOT = count($notificationOT);
            $view->with([
                'registerOT' => $registerOT,
                'unreadNotificationOT' => $unreadNotificationOT
            ]);
        });
    }
}
