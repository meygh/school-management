<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\School\PatchRequest;
use App\Http\Requests\School\StoreRequest;
use App\Models\School;
use Illuminate\Http\Request;
use App\Http\Resources\PrincipleResource;

class SchoolController extends BaseController
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
        $schools = School::paginate();

        return PrincipleResource::collection($schools);
    }

    /**
     * Store a newly School in storage.
     *
     * @param StoreRequest $request
     *
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $school = School::create($request->validated());

        return $this->sendResponse(
            new PrincipleResource($school),
            "مدرسه {$school->name} با موفقیت افزوده شد",
            201
        );
    }

    /**
     * Display the specified School.
     *
     * @param School $school
     *
     * @return PrincipleResource
     */
    public function show(School $school)
    {
        return new PrincipleResource($school);
    }

    /**
     * Update the specified School in storage.
     *
     * @param PatchRequest $request
     * @param School $school
     *
     * @return PrincipleResource|\Illuminate\Http\JsonResponse
     */
    public function update(PatchRequest $request, School $school)
    {
        if (!$school) {
            return $this->sendError('مدرسه مورد نظر یافت نشد!');
        }

        $school->update($request->validated());

        return $this->sendResponse(
            new PrincipleResource($school),
            "مدرسه {$school->name} با موفقیت ویرایش شد",
            204
        );
    }

    /**
     * Remove the specified School from storage.
     *
     * @param School $school
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(School $school)
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
