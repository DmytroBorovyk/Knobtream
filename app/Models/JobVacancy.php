<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobVacancy extends Model
{
    use SoftDeletes;

    protected $table = 'job_vacancies';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'response_count'
    ];

    public $keyType = 'string';

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(JobVacancyResponse::class,'job_id');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class,'vacancy_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class,'liked_id')->where('liked_type', 'job');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'vacancy_tag', 'vacancy_id', 'tag_id');
    }

    public function likedByUsers()
    {
        return $this->morphToMany(User::class, 'liked', 'likes');
    }

    public function addReviewsCount()
    {
        $this->response_count++;
        $this->save();
    }

    public function removeReviewsCount()
    {
        $this->response_count--;
        $this->save();
    }
}
