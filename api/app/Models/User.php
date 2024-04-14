<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Status;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 *
 * @property string $id
 * @property string $firstname
 * @property string $lastname
 * @property string $username
 * @property string $email
 * @property integer $status
 *
 * @property string $fullName
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @property array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @property array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @property array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserStatus::class
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Hash::make($value),
        );
    }

    /**
     * Filter principle users.
     * @param Builder $query
     *
     * @return void
     */
    public function scopePrinciples(Builder $query): void
    {
        $query->where('status', UserStatus::PRINCIPLE);
    }

    /**
     * Filter teacher users.
     * @param Builder $query
     *
     * @return void
     */
    public function scopeTeachers(Builder $query): void
    {
        $query->where('status', UserStatus::TEACHER);
    }

    /**
     * Filter student users.
     * @param Builder $query
     *
     * @return void
     */
    public function scopeStudents(Builder $query): void
    {
        $query->where('status', UserStatus::STUDENT);
    }

    public function isActive(): bool
    {
        return !$this->isDisabled();
    }

    public function isDisabled(): bool
    {
        return $this->checkStatus(UserStatus::INACTIVE);
    }

    public function isAdmin(): bool
    {
        return $this->checkStatus(UserStatus::ADMIN);
    }

    public function isPrinciple(): bool
    {
        return $this->checkStatus(UserStatus::PRINCIPLE);
    }

    public function isTeacher(): bool
    {
        return $this->checkStatus(UserStatus::TEACHER);
    }

    public function isStudent(): bool
    {
        return $this->checkStatus(UserStatus::STUDENT);
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function principle()
    {
        return $this->hasOne(SchoolPrinciple::class, 'user_id', 'id');
    }

    public function teacher()
    {
        return $this->hasOne(SchoolTeacher::class, 'user_id')
            ->where('status', Status::ACTIVE);
    }

    public function student()
    {
        return $this->hasOne(SchoolStudent::class, 'user_id')
            ->where('status', Status::ACTIVE);
    }

    protected function checkStatus(UserStatus $status): bool
    {
        return $this->status == $status;
    }
}
