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

class SchoolClassroomController extends BaseController
{
    /**
     * Display a listing of the SchoolClassroom.
     *
     * @param Request $request
     * @param School|null $school
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, School $school = null)
    {
        if (!Gate::allows('admin') && !Gate::allows('school-principle', $school)) {
            return $this->sendError('شما اجازه مشاهده کلاس های این مدرسه را ندارید!', [], 403);
        }

        $schools = $school->classrooms()->with('teacher')->paginate();

        return SchoolClassroomResource::collection($schools);
    }

    public function listOfStudents(Request $request, SchoolClassroom $classroom = null)
    {
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
     * @param School $school
     *
     * @return mixed
     */
    public function store(StoreRequest $request, School $school)
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
     * @param SchoolClassroom $classroom
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(School $school, SchoolClassroom $classroom)
    {
        $classroom->loadMissing('school', 'teacher');

        return $this->sendResponse(new SchoolClassroomResource($classroom));
    }

    /**
     * Update the specified SchoolClassroom in storage.
     *
     * @param PatchRequest $request
     * @param School $school
     * @param SchoolClassroom $classroom
     *
     * @return SchoolClassroomResource|\Illuminate\Http\JsonResponse
     */
    public function update(PatchRequest $request, School $school, SchoolClassroom $classroom)
    {
        $classroom->update($request->validated());

        return $this->sendResponse(
            new SchoolClassroomResource($classroom),
            "کلاس '{$classroom->name}' ویرایش شد"
        );
    }

    /**
     * Remove the specified SchoolClassroom from storage.
     *
     * @param School $school
     * @param SchoolClassroom $classroom
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(School $school, SchoolClassroom $classroom)
    {
        if ($classroom->delete()) {
            return $this->sendResponse(null, 'مدرسه ' . $classroom->name . ' حذف شد.', 204);
        }

        return $this->sendError('حذف مدرسه با خطا مواجه شد!', [], 500);
    }
}
