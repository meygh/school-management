<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    public function create(User $user)
    {
        return $this->isAuthorized($user);
    }

    public function update(User $user)
    {
        return $this->isAuthorized($user);
    }

    public function destroy(User $user)
    {
        return $this->isAuthorized($user);
    }

    protected function isAuthorized(User $user): Response
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return Response::allow();
        }

        return Response::deny('شما دسترسی لازم به این عملیات را ندارید!');
    }
}
