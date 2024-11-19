<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\Ticket;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Share the unread tickets count to all views
        View::composer('*', function ($view) {
            $unreadTicketsCount = Ticket::where('is_read', false)->count();
            $view->with('unreadTicketsCount', $unreadTicketsCount);
        });
    }
}
