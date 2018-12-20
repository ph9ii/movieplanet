<?php

namespace App\Providers;

use App\User;
use Carbon\Carbon;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-action', function ($user) {
            return $user->isAdmin();
        });

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(60));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30)); // Time to use the refresh token
        Passport::enableImplicitGrant();

        Passport::tokensCan([
            'rating-movie'      => 'Rating a specific movie',
            'manage-movies'     => 'create, read, update, and delete movies (CRUD)',
            'manage-account'    => 'Read your account data, id, name, email, if verified, and if admin (cannot read password). Modify your account data (email, and password). cannot delete your account',
            'read-general'      => 'Read general information like user ratings)',
        ]);
    }
}
