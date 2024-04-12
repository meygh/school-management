<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserProfile
 * @package App\Models
 *
 * Attributes:
 * @property int $user_id
 * @property ?string $national_code
 * @property ?string $mobile
 * @property ?string $phone
 * @property ?string $address
 * @property ?string $zipcode
 * @property int $status
 *
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class UserProfile extends Model
{
    use HasFactory, HasTimestamps, CreatedUpdatedBy;

    protected $table = 'user_profiles';

    protected $with = ['user'];

    protected $fillable = [
        'user_id',
        'national_code',
        'mobile',
        'phone',
        'address',
        'zipcode',
        'cv',
    ];

    protected $requiredFields = [
        'national_code',
        'mobile',
        'address',
        'zipcode',
    ];

    /**
     * The attributes that should be cast.
     *
     * @property array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function isCompleted(): bool
    {
        foreach ($this->requiredFields as $field) {
            if (!$this->{$field}) {
                return false;
            }
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongTo(User::class, 'updated_by', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }
}
