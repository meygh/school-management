<?php

namespace App\Providers;

use App\Enums\UserStatus;
use App\Models\School;
use App\Models\SchoolClassroom;
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
        $deny_access_msg = __('messages.ِYou are not allowed to perform this action!');
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
            'AdminOrPrinciple',
            function (User $user) use ($deny_access_msg) {
                if ($user->isAdmin() || $user->isPrinciple()) {
                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'principle',
            function (User $user, Model $model = null) use ($deny_access_msg) {
                if ($user->isPrinciple() && ($principle = $user->principle)) {
                    if ($model?->school_id && $model->school_id != $principle->school_id) {
                        return Response::deny($deny_access_msg);
                    }

                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'teacher',
            function (User $user, Model $model = null) use ($deny_access_msg) {
                if ($user->isTeacher() && ($teacher = $user->teacher)) {
                    if ($model?->classroom_id && $model->classroom_id != $teacher->classroom_id) {
                        return Response::deny($deny_access_msg);
                    }

                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'school-principle',
            function (User $user, School $school = null) use ($deny_access_msg) {
                if ($user->isPrinciple() && ($principle = $user->principle)) {
                    if ($school && $school->id != $principle->school_id) {
                        return Response::deny($deny_access_msg);
                    }

                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );

        Gate::define(
            'owned-classroom',
            function (User $user, SchoolClassroom $classroom = null) use ($deny_access_msg) {
                if ($user->isPrinciple() && ($principle = $user->principle)) {
                    if ($classroom && $classroom->school_id != $principle->school_id) {
                        return Response::deny($deny_access_msg);
                    }

                    return Response::allow();
                }

                if ($user->isTeacher() && ($teacher = $user->teacher)) {
                    if ($classroom && $classroom->id != $teacher->classroom_id) {
                        return Response::deny($deny_access_msg);
                    }

                    return Response::allow();
                }

                return Response::deny($deny_access_msg);
            }
        );
    }
}
