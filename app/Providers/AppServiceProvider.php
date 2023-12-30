<?php

namespace App\Providers;

use App\Notifications\Sms\Operators\Ghasedak;
use App\Notifications\Sms\Operators\Kavehnegar;
use App\Notifications\Sms\Sms;
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
        app()->bind('SMS', function (){
            $configDriver = strtolower(env('SMS_DRIVER'));

            $availableDrivers = [
                'kavehnegar' => Kavehnegar::class,
                'ghasedak' => Ghasedak::class,
            ];

            $driverClass = $availableDrivers[$configDriver] ?? Kavehnegar::class;

            return new Sms(app($driverClass));
        });
    }
}
