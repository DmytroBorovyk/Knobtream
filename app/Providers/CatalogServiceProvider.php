<?php

namespace App\Providers;

use App\Http\Services\JobCatalogService;
use App\Http\Services\JobResponseService;
use Carbon\Laravel\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind(JobCatalogService::class, function () {
            return new JobCatalogService();
        });

        $this->app->bind(JobResponseService::class, function () {
            return new JobResponseService();
        });
    }
}
