<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\IssueSubject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class DepartmentResource
 * @package App\Http\Resources
 *
 * Attributes:
 * @property int $id
 * @property string $zone_id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * Relations:
 * @property IssueSubject $zone
 * @property ?User $createdBy
 * @property ?User $updatedBy
 * @property ?User $deletedBy
 */
class DepartmentResource extends JsonResource
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
            'zoneId' => $this->zone_id,
            'name' => $this->name,
            'zone' => new IssueSubjectResource($this->whenLoaded('zone'))
        ];
    }
}
