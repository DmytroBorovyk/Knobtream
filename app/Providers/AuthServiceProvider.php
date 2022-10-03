<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Http\Services\AuthService;
use App\Models\JobVacancy;
use App\Policies\JobVacancyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Queue\Jobs\Job;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        JobVacancy::class => JobVacancyPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }

    public function register()
    {
        $this->app->bind(AuthService::class, function () {
            return new AuthService();
        });
    }
}
