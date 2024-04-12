<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class PrinciplePolicy
{
    public function create(User $user)
    {
        return $this->isAuthorized($user);
    }

    public function update(User $user, Model $model)
    {
        if ($this->isAuthorized($user) && $model->user_id == $user->id) {
            return Response::allow();
        }

        return Response::deny('شما ایجاد کننده این آیتم نیستید!');
    }

    public function destroy(User $user, Model $model)
    {
        if ($this->isAuthorized($user) && $model->user_id == $user->id) {
            return Response::allow();
        }

        return Response::deny('شما ایجاد کننده این آیتم نیستید!');
    }

    protected function isAuthorized(User $user): Response
    {
        if ($user->isDepartmentExpert()) {
            return Response::allow();
        }

        return Response::deny('شما دسترسی لازم به این عملیات را ندارید!');
    }
}
