<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserProfileResource
 * @package App\Http\Resources
 *
 * Attributes:
 * @property int $user_id
 *
 * @property string|null $national_code
 * @property string|null $avatar
 * @property string|null $personal_photo
 * @property string|null $mobile
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $work_address
 * @property string|null $cv
 *
 * @property int $education_certificate
 * @property int $status
 */
class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nationalCode' => $this->national_code,
            'avatar' => $this->avatar,
            'personalPhoto' => $this->personal_photo,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'address' => $this->address,
            'workAddress' => $this->work_address,
            'cv' => $this->cv,
            'educationCertificate' => $this->education_certificate,
            'status' => $this->status,
        ];
    }
}
