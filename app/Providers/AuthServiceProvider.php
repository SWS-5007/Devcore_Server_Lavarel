<?php

namespace App\Providers;

use App\Lib\Auth\AccessTokenGuard;
use App\Lib\Auth\TokenToUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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
        //register the token auth provider for the api
        Auth::extend('token', function ($app, $name, array $config) {
            // automatically build the DI, put it as reference
            app()->forgetInstance(TokenToUserProvider::class);
            $userProvider = app(TokenToUserProvider::class);
            $request = app('request');
            return new AccessTokenGuard($userProvider, $request, $config);
        });

        Gate::before(function ($user, $ability) {
            if ($user) {
                if ($user->is_super_admin) {
                    return true;
                }
            }
        });



        Gate::define('notifications/projectUpdate', function ($user, $project) {
            return $project->users->where('id', $user->id)->first()!==null;
        });

        Gate::define('core/company/read', function ($user, $author) {
            return $author->company_id === $user->companyId;
        });



        //who can evaluate an idea
        Gate::define('core/project/evaluateIdea', function ($user, $record) {
            return $record->author_id === $user->id;
        });


        //who can view private data of users
        Gate::define('auth/user/privateData', function ($user, $arg) {
            return $arg->id === $user->id || (($user->hasRole("Company Admin") || $user->hasRole('Company Manager')) && $arg->company_id === $user->company_id);
        });

        //who can view private data of
    }
}
