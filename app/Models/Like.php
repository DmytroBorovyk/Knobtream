<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $table = 'likes';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'user_id',
        'liked_id',
    ];

    protected $casts = [
        'user_id' => 'string',
        'liked_id' => 'string',
    ];

    public $keyType = 'string';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function liked_job(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'liked_id');
    }

    public function liked_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'liked_id');
    }
}
