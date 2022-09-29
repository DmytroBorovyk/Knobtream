<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobVacancyResponse extends Model
{
    use SoftDeletes;

    protected $table = 'job_reviews';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'user_id',
        'job_id',
        'review_text',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_id');
    }
}
