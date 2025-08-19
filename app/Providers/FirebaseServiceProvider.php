<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
{
    $this->app->singleton(\Kreait\Firebase\Auth::class, function ($app) {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials_file'));
        return $factory->createAuth();
    });
}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
