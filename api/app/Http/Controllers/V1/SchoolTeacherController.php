<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserStatus;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\PatchUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Http\Requests\SchoolTeacher\StoreRequest;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\UserResource;
use App\Models\SchoolClassroom;
use App\Models\SchoolTeacher;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolTeacherController extends BaseController
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
        $teachers = User::teachers()->paginate();

        return UserResource::collection($teachers);
    }

    /**
     * Store a newly SchoolClassroom in storage.
     *
     * @param StoreUserRequest $request
     *
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        $inputs = $request->validated();
        $inputs['status'] = UserStatus::TEACHER;

        $teacherUserAccount = User::create($inputs);

        return $this->sendResponse(
            new UserResource($teacherUserAccount),
            "معلم مدرسه با موفقیت افزوده شد",
            201
        );
    }

    /**
     * Assign pear to pear school and teacher.
     *  every school can be assigned only to one teacher.
     *
     * @param StoreRequest $request
     * @param User $userTeacher
     * @param SchoolClassroom $classroom
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignClassroom(StoreRequest $request, User $userTeacher, SchoolClassroom $classroom)
    {
        $teacher = new SchoolTeacher($request->validated());

        if ($teacher = $teacher->assignClassroom()) {
            $teacher->loadMissing(['school', 'classroom']);

            return $this->sendResponse(
                new TeacherResource($teacher),
                "مدرسه با موفقیت به این معلم تخصیص داده شد",
                201
            );
        }

        return $this->sendError('تخصیص مدرسه به معلم مدرسه با خطا مواجه شد!', [], 500);
    }

    /**
     * Display the specified School by the given user id.
     *
     * @param SchoolTeacher $userTeacher
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByUserId(SchoolTeacher $userTeacher)
    {
        return $this->sendResponse(new TeacherResource($userTeacher));
    }

    /**
     * Display the specified School.
     *
     * @param SchoolTeacher $teacher
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SchoolTeacher $teacher)
    {
        $teacher->loadMissing(['school', 'classroom']);

        return $this->sendResponse(new TeacherResource($teacher));
    }

    /**
     * Update the specified SchoolClassroom in storage.
     *
     * @param PatchUserRequest $request
     * @param SchoolTeacher $teacher
     *
     * @return TeacherResource|\Illuminate\Http\JsonResponse
     */
    public function update(PatchUserRequest $request, SchoolTeacher $teacher)
    {
        if (!$teacher) {
            return $this->sendError('معلم مدرسه مورد نظر یافت نشد!');
        }

        $teacher->user->update($request->validated());

        return $this->sendResponse(
            new TeacherResource($teacher),
            "معلم شناسه {$teacher->id} با موفقیت ویرایش شد",
            204
        );
    }

    /**
     * Remove the specified teacher from the current school and disable his account.
     *
     * @param User $userTeacher
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(User $userTeacher)
    {
        if ($userTeacher->delete()) {
            return response()->noContent();
        }

        return $this->sendError('حذف معلم مدرسه با خطا مواجه شد!', [], 500);
    }
}
