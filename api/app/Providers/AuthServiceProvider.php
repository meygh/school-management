<?php

namespace App\Providers;

use App\Enums\UserStatus;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\StudentPolicy;
use App\Policies\PrinciplePolicy;
use App\Policies\TrusteePolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // AdminPolicy models
        'App\Models\School' => AdminPolicy::class,
        'App\Models\SchoolClassroom' => AdminPolicy::class,
        'App\Models\SchoolPrinciple' => AdminPolicy::class,

        // PrinciplePolicy models
        'App\Models\SchoolTeacher' => PrinciplePolicy::class,
        'App\Models\SchoolStudent' => PrinciplePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $deny_access_msg = __('ِYou are not allowed to perform this action!');
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Gate::define(
            'admin',
            function (User $user, Model $model = null) use ($deny_access_msg) {
                if ($user->isAdmin()) {
                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'manage-schools',
            function (User $user, Model $model = null) use ($deny_access_msg) {
                if ($user->isAdmin()) {
                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'manage-classroom',
            function (User $user, Model $model = null) use ($deny_access_msg) {
                if ($user->isAdmin()) {
                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'manage-principles',
            function (User $user, Model $model = null) use ($deny_access_msg) {
                if ($user->isAdmin()) {
                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'manage-teachers',
            function (User $user, Model $model = null) use ($deny_access_msg) {
                if ($user->isPrinciple()) {
                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'manage-students',
            function (User $user, Model $model = null) use ($deny_access_msg) {
                if ($user->isPrinciple() || $user->isTeacher()) {
                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );
    }
}
