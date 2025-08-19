<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\Kreait\Firebase\Auth::class, function ($app) {
            $serviceAccountPath = config('firebase.credentials.file');

            
            Log::info('🔥 Firebase path check', [
                'path' => $serviceAccountPath,
                'exists' => file_exists($serviceAccountPath),
            ]);

            if (empty($serviceAccountPath)) {
                Log::error('❌ Firebase service account path is NULL. Please check your .env or config/firebase.php');
                throw new \Exception('Firebase service account path is missing.');
            }

            $factory = (new Factory)
                ->withServiceAccount($serviceAccountPath)
                ->withProjectId(config('firebase.default_project_id'));

            return $factory->createAuth();
        });
    }

    public function boot(): void
    {
        //
    }
}
