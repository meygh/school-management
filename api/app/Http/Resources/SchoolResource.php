<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
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
            'name' => $this->name,
            'zone' => $this->zone,
            'principle' => new PrincipleResource($this->whenLoaded('principle')),
            'statusId' => $this->status,
            'status' => $this->status?->label(),
        ];
    }
}
