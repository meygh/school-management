<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolClassroomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'schoolId' => $this->school_id,
            'name' => $this->name,
            'school' => new SchoolResource($this->whenLoaded('school')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
        ];
    }
}
