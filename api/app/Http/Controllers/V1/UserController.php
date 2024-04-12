<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\PatchUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        /*$per_page = (int) $request->get('per_page', 10);
        if (!$per_page) {
            $per_page = 10;
        }*/

        $users = User::with('profile')->paginate(10);

        return UserResource::collection($users);
    }

    public function find($term = '')
    {
        $term = "%{$term}%";
        $users = User::where('firstname', 'LIKE', $term)
            ->orWhere('lastname', 'LIKE', $term)
            ->orWhere('username', 'LIKE', $term)
            ->orWhere('email', 'LIKE', $term)
            ->get();

        return UserResource::collection($users);
    }

    /**
     * Store a newly User in storage.
     *
     * @param StoreUserRequest $request
     *
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        $result = false;
        $user = User::create($request->except('profile'));

        if (!$user) {
            return $this->sendError('ایجاد اطلاعات کاربر با خطا مواجه شد!');
        }

        if ($user_profile_attrs = $request->get('profile')) {
            $result = $user->profile()->update($user_profile_attrs);
        }

        if ($result) {
            return $this->sendResponse(new UserResource($user), 'کاربر ویرایش شد.');
        }

        return $this->sendError('ایجاد اطلاعات کاربر با خطا مواجه شد!');
    }

    /**
     * Display the specified User.
     *
     * @param User $user
     *
     * @return UserResource
     */
    public function show(User $user)
    {
        $user->load('profile');

        return new UserResource($user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param PatchUserRequest $request
     * @param User $user
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function update(PatchUserRequest $request, User $user)
    {
        if (!$user) {
            return $this->sendError('کاربر مورد نظر یافت نشد!');
        }

        $result = false;

        if ($user_attrs = $request->except('profile')) {
            $result = $user->update($user_attrs);
        }

        if ($user_profile_attrs = $request->get('profile')) {
            $result = $user->profile()->update($user_profile_attrs);
        }

        if ($result) {
            return $this->sendResponse(new UserResource($user), 'کاربر ویرایش شد.');
        }

        return $this->sendError('ویرایش اطلاعات کاربر با خطا مواجه شد!');
    }

    /**
     * Remove the specified User from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!$user) {
            return $this->sendError('کاربر مورد نظر یافت نشد!');
        }

        if ($user->delete()) {
            return $this->sendResponse(null, 'کاربر ' . $user->fullName . ' حذف شد.');
        }

        return $this->sendError('حذف کاربر با خطا مواجه شد!');
    }
}
