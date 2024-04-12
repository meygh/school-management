<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserStatus;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\PatchUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Http\Requests\SchoolPrinciple\StoreRequest;
use App\Http\Resources\UserResource;
use App\Models\School;
use App\Models\SchoolPrinciple;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\PrincipleResource;
use Illuminate\Support\Facades\DB;

class SchoolPrincipleController extends BaseController
{
    /**
     * Display a listing of the School.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $principles = User::principles()->paginate();

        return UserResource::collection($principles);
    }

    /**
     * Store a newly SchoolPrinciple in storage.
     *
     * @param StoreUserRequest $request
     *
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        $inputs = $request->validated();
        $inputs['status'] = UserStatus::PRINCIPLE;

        $principleUserAccount = User::create($inputs);

        return $this->sendResponse(
            new UserResource($principleUserAccount),
            "مدیر مدرسه با موفقیت افزوده شد",
            201
        );
    }

    /**
     * Assign pear to pear school and principle.
     *  every school can be assigned only to one principle.
     *
     * @param StoreRequest $request
     * @param User $user
     * @param School $school
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignSchool(StoreRequest $request, User $user, School $school)
    {
        $principle = new SchoolPrinciple($request->validated());

        if ($principle->assignSchool()) {
            $principle->loadMissing('school');

            return $this->sendResponse(
                new PrincipleResource($principle),
                "مدرسه با موفقیت به این مدیر تخصیص داده شد",
                201
            );
        }

        return $this->sendError('تخصیص مدرسه به مدیر مدرسه با خطا مواجه شد!', [], 500);
    }

    /**
     * Display the specified School by the given user id.
     *
     * @param User $userPrinciple
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByUserId(SchoolPrinciple $userPrinciple)
    {
        return $this->sendResponse(new PrincipleResource($userPrinciple));
    }

    /**
     * Display the specified School.
     *
     * @param SchoolPrinciple $principle
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SchoolPrinciple $principle)
    {
        return $this->sendResponse(new PrincipleResource($principle));
    }

    /**
     * Update the specified SchoolPrinciple in storage.
     *
     * @param PatchUserRequest $request
     * @param SchoolPrinciple $principle
     *
     * @return PrincipleResource|\Illuminate\Http\JsonResponse
     */
    public function update(PatchUserRequest $request, SchoolPrinciple $principle)
    {
        if (!$principle) {
            return $this->sendError('مدیر مدرسه مورد نظر یافت نشد!');
        }

        $request->merge(['status' => UserStatus::PRINCIPLE]);
        $principle->user->update($request->validated());

        return $this->sendResponse(
            new PrincipleResource($principle),
            "مدیر شناسه {$principle->id} با موفقیت ویرایش شد",
            204
        );
    }

    /**
     * Remove the specified principle from the current school and disable his account.
     *
     * @param SchoolPrinciple $principle
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(SchoolPrinciple $principle)
    {
        if (!$principle) {
            return $this->sendError('مدیر مدرسه مورد نظر یافت نشد!');
        }

        DB::beginTransaction();
        $principle->user->status = UserStatus::INACTIVE;

        if ($principle->delete()) {
            DB::commit();

            return $this->sendResponse(null, 'مدیر شناسه ' . $principle->id . ' حذف شد.', 204);
        }

        DB::rollBack();

        return $this->sendError('حذف مدیر مدرسه با خطا مواجه شد!', [], 500);
    }
}
