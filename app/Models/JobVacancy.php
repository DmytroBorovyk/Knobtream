<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
