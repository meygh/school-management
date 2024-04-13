<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SchoolClassroom\PatchRequest;
use App\Http\Requests\SchoolClassroom\StoreRequest;
use App\Http\Resources\StudentResource;
use App\Models\School;
use App\Models\SchoolClassroom;
use Illuminate\Http\Request;
use App\Http\Resources\SchoolClassroomResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class SchoolClassroomController extends BaseController
{
    /**
     * Display a listing of the SchoolClassroom.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, School $school = null)
    {
        if (!$school) {
            return $this->sendError('مدرسه مورد نظر یافت نشد!');
        }

        if (!Gate::allows('school-principle', $school)) {
            return $this->sendError('شما اجازه مشاهده کلاس های این مدرسه را ندارید!', [], 403);
        }

        $schools = $school->classrooms()->with('teacher')->paginate();

        return SchoolClassroomResource::collection($schools);
    }

    public function listOfStudents(Request $request, SchoolClassroom $classroom = null)
    {
        if (!$classroom) {
            return $this->sendError('کلاس مورد نظر یافت نشد!');
        }

        if (!Gate::allows('owned-classroom', $classroom)) {
            return $this->sendError('شما اجازه مشاهده این کلاس را ندارید!', [], 403);
        }

        $students = $classroom->students()->paginate();

        return StudentResource::collection($students);
    }

    /**
     * Store a newly SchoolClassroom in storage.
     *
     * @param StoreRequest $request
     *
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $school = SchoolClassroom::create($request->validated());

        return $this->sendResponse(
            new SchoolClassroomResource($school),
            "مدرسه {$school->name} با موفقیت افزوده شد",
            201
        );
    }

    /**
     * Display the specified SchoolClassroom.
     *
     * @param SchoolClassroom $school
     *
     * @return SchoolClassroomResource
     */
    public function show(SchoolClassroom $school)
    {
        return new SchoolClassroomResource($school);
    }

    /**
     * Update the specified SchoolClassroom in storage.
     *
     * @param PatchRequest $request
     * @param SchoolClassroom $school
     *
     * @return SchoolClassroomResource|\Illuminate\Http\JsonResponse
     */
    public function update(PatchRequest $request, SchoolClassroom $school = null)
    {
        if (!$school) {
            return $this->sendError('مدرسه مورد نظر یافت نشد!');
        }

        $school->update($request->validated());

        return $this->sendResponse(
            new SchoolClassroomResource($school),
            "مدرسه {$school->name} با موفقیت ویرایش شد",
            204
        );
    }

    /**
     * Remove the specified SchoolClassroom from storage.
     *
     * @param SchoolClassroom $school
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(SchoolClassroom $school)
    {
        if (!$school) {
            return $this->sendError('مدرسه مورد نظر یافت نشد!');
        }

        if ($school->delete()) {
            return $this->sendResponse(null, 'مدرسه ' . $school->name . ' حذف شد.', 204);
        }

        return $this->sendError('حذف مدرسه با خطا مواجه شد!', [], 500);
    }
}
