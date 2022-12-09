<?php

namespace App\Providers;

use App\Models\Eloquent\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('editor', function (User $user) {
            $roleId = (int)$user->getRole()->role_id;
            if ($roleId === 1 || $roleId === 2) {
                return true;
            } else {
                return false;
            }
        });
    }
}
