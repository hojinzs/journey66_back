<?php

namespace App\Providers;

use App\Guards\AdminGuard as AdminGuard;
use Illuminate\Contracts\Auth\UserProvider as UserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('admin', function ($app, $name, array $config) {

            return new AdminGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request')
            );
        });

    }
}
