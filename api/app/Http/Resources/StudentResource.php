<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\IssueSubject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PrincipleResource
 * @package App\Http\Resources
 *
 * Attributes:
 * @property int $id
 * @property int $school_id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * Relations:
 * @property User $user
 * @property ?User $createdBy
 * @property ?User $updatedBy
 * @property ?User $deletedBy
 */
class StudentResource extends JsonResource
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
            'name' => $this->user?->fullName,
            'school' => new SchoolResource($this->whenLoaded('school')),
            'classroom' => new SchoolClassroomResource($this->whenLoaded('classroom')),
            'user' => new UserResource($this->whenLoaded('user')),
            'statusId' => $this->status,
            'status' => $this->status?->label(),
        ];
    }
}
