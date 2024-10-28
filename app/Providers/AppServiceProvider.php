<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event; // Tambahkan import untuk Event
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Azure\Provider; // Pastikan import Provider sesuai dengan ekstensi yang digunakan

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
        // Daftarkan event listener untuk Socialite
        Event::listen(SocialiteWasCalled::class, function (SocialiteWasCalled $event) {
            $event->extendSocialite('azure', Provider::class); // Pastikan class sesuai
        });
        
    }
}
