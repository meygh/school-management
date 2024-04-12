<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Class SchoolPrinciple
 * @package App\Models
 *
 * Attributes:
 * @property int $id
 * @property int $school_id
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
 * @property User $user
 */
class SchoolPrinciple extends Model
{
    use HasFactory;

    protected $with = ['school', 'user'];

    protected $fillable = ['school_id', 'user_id', 'status'];

    public $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'status' => Status::class,
    ];

    /**
     * Assign pear to pear school and principle.
     * every school can be assigned only to one principle.
     * @return SchoolPrinciple|$this|null
     */
    public function assignSchool(): ?SchoolPrinciple
    {
        if (!$this->school_id || !$this->user_id) {
            return null;
        }
        DB::beginTransaction();

        try {
            // Detach all current schools of the given principle.
            self::where('user_id', $this->user_id)->delete();

            if ($this->save()) {
                DB::commit();

                return $this;
            }
        } catch (\Exception $e) {}

        DB::rollBack();

        return null;
    }

    /**
     * Returns current active school.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    /**
     * Returns list of previous schools if has any.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prevSchools()
    {
        return $this->belongsTo(School::class, 'school_id', 'id')
            ->where('status', Status::INACTIVE);
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
