<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SchoolStudent
 * @package App\Models
 *
 * Attributes:
 * @property int $id
 * @property int $school_id
 * @property int $classroom_id
 * @property int $user_id
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * Relations:
 * @property School $school
 * @property SchoolClassroom $classroom
 * @property User $user
 */
class SchoolStudent extends Model
{
    use HasFactory, HasTimestamps, CreatedUpdatedBy;

    protected $with = ['classroom', 'user'];

    protected $fillable = ['classroom_id', 'user_id', 'status'];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'status' => Status::class,
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(SchoolClassroom::class, 'school_id', 'id');
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
