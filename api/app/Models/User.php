<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'status' => Status::class
    ];

    public function isAcitvated(): bool
    {
        return $this->checkStatus();
    }

    public function isDisabled(): bool
    {
        return $this->checkStatus(Status::INACTIVE);
    }

    public function isProfileCompleted()
    {
        if ($this->isDisabled() || !$this->profile) {
            return false;
        }

        return $this->profile?->isCompleted();
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function setProfileAttribute($values)
    {
        $this->profile()->update($values);
    }

    protected function checkStatus(Status $status = Status::ACTIVE): bool
    {
        return $this->status === $status->value;
    }
}
