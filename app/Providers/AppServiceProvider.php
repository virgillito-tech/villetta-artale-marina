<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // \Socialite::extend('apple', function ($app) {
        //     $config = $app['config']['services.apple'];
        //     return (new \SocialiteProviders\Apple\Provider(
        //         $config['client_id'],
        //         $config['client_secret'],
        //         $config['redirect']
        //     ))->setHttpClient(new \GuzzleHttp\Client());
        // });
    }

}
