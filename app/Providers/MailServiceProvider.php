<?php

namespace App\Providers;

use App\Http\Services\MailService;
use Carbon\Laravel\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton(MailService::class, function () {
            return new MailService();
        });
    }
}
