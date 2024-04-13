<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class SchoolPrinciple
 * @package App\Models
 *
 * Attributes:
 * @property int $id
 * @property int $school_id
 * @property int $name
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
 * @property ?SchoolTeacher $teacher
 * @property SchoolStudent[] $students
 */
class SchoolClassroom extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'name', 'status'];

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

    public function teacher()
    {
        return $this->hasOne(SchoolTeacher::class, 'classroom_id');
    }

    public function students()
    {
        return $this->hasMany(SchoolStudent::class, 'classroom_id');
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
