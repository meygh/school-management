<?php

namespace App\Http\Controllers\V1;

use App\Enums\UserStatus;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\PatchUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Http\Requests\SchoolStudent\StoreRequest;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\UserResource;
use App\Models\SchoolClassroom;
use App\Models\SchoolStudent;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolStudentController extends BaseController
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
        $teachers = User::students()->paginate();

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
        $inputs['status'] = UserStatus::STUDENT;

        $teacherUserAccount = User::create($inputs);

        return $this->sendResponse(
            new UserResource($teacherUserAccount),
            "دانش آموز با موفقیت افزوده شد",
            201
        );
    }

    /**
     * Assign pear to pear school and teacher.
     *  every school can be assigned only to one teacher.
     *
     * @param StoreRequest $request
     * @param User $userStudent
     * @param SchoolClassroom $classroom
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignClassroom(StoreRequest $request, User $userStudent, SchoolClassroom $classroom)
    {
        $student = new SchoolStudent($request->validated());

        if ($student = $student->assignClassroom()) {
            $student->loadMissing(['school', 'classroom']);

            return $this->sendResponse(
                new TeacherResource($student),
                "مدرسه با موفقیت به این دانش آموز تخصیص داده شد",
                201
            );
        }

        return $this->sendError('ثبت نام دانش آموز در کلاس با خطا مواجه شد!', [], 500);
    }

    /**
     * Display the specified School.
     *
     * @param SchoolStudent $student
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SchoolStudent $student)
    {
        return $this->sendResponse(new TeacherResource($student));
    }

    /**
     * Display the specified School by the given user id.
     *
     * @param User $userStudent
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByUserId(User $userStudent)
    {
        if (!$userStudent) {
            return $this->sendError('دانش آموز مورد نظر یافت نشد!');
        }

        return $this->sendResponse(new UserResource($userStudent));
    }

    /**
     * Update the specified SchoolClassroom in storage.
     *
     * @param PatchUserRequest $request
     * @param User $userStudent
     *
     * @return TeacherResource|\Illuminate\Http\JsonResponse
     */
    public function update(PatchUserRequest $request, User $userStudent)
    {
        if (!$userStudent) {
            return $this->sendError('دانش آموز مورد نظر یافت نشد!');
        }

        $userStudent->update($request->validated());

        return $this->sendResponse(
            new TeacherResource($userStudent),
            "دانش آموز شناسه {$userStudent->id} با موفقیت ویرایش شد",
            204
        );
    }

    /**
     * Remove the specified teacher from the current school and disable his account.
     *
     * @param User $userStudent
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(User $userStudent)
    {
        if ($userStudent->delete()) {
            return response()->noContent();
        }

        return $this->sendError('حذف دانش آموز با خطا مواجه شد!', [], 500);
    }
}
