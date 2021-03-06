<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\JWTAuth;

class JWTAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once  app_path().'/Helpers/JWTAuth.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
