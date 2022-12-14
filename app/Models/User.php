<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
    ];

    public $keyType = 'string';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'user_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(JobVacancyResponse::class, 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function likedJobs()
    {
        return $this->morphedByMany(JobVacancy::class, 'liked','likes');
    }

    public function likedUsers()
    {
        return $this->morphedByMany(User::class, 'liked', 'likes');
    }

    public function likedByUsers()
    {
        return $this->morphToMany(User::class, 'liked', 'likes');
    }

    public function addCoins(int $amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    public function removeCoins(int $amount)
    {
        $this->balance -= $amount;
        $this->save();
    }
}
