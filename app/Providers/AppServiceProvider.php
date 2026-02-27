<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Http\Kernel;
use App\Http\Middleware\EncryptCookies;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Força o Laravel a usar a sua EncryptCookies
        $this->app->singleton(\Illuminate\Cookie\Middleware\EncryptCookies::class, EncryptCookies::class);
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
