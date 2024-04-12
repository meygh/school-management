<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class School
 * @package App\Models
 *
 * Attributes:
 * @property int $id
 * @property string $zone
 * @property string $name
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * Relations:
 * @property ?SchoolPrinciple $principle
 * @property SchoolClassroom[] $classrooms
 * @property ?User $createdBy
 * @property ?User $updatedBy
 * @property ?User $deletedBy
 */
class School extends Model
{
    use HasFactory;

    protected $fillable = ['zone', 'name'];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'status' => Status::class,
    ];

    public function principle()
    {
        return $this->hasOne(SchoolPrinciple::class, 'school_id');
    }

    public function classrooms()
    {
        return $this->hasMany(SchoolClassroom::class, 'school_id');
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
