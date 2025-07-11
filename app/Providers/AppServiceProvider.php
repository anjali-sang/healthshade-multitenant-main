<?php

namespace App\Providers;

use App\Models\Organization;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Disable MySQL strict mode and set default string length
        config()->set('database.connections.mysql.strict', false);
        Schema::defaultStringLength(191);

        // Share theme and organization session values with all views
        View::composer('*', function ($view) {
            $user = auth()->user(); // Get the authenticated user

            if ($user && $user->role_id == '1') {
                $view->with('themeClass', $user->theme_color ?? 'yellow');
                if ($user->organization) {
                    session([
                        'currency' => $user->organization->currency,
                        'timezone' => $user->organization->timezone,
                        'date_format' => $user->organization->date_format,
                        'time_format' => $user->organization->time_format,
                    ]);
                }
            } else {
                $view->with('themeClass', $user?->organization?->theme ?? 'yellow');
            }
        });
    }
}
