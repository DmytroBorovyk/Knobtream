<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    protected $table = 'emails';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'vacancy_id',
    ];

    public $keyType = 'string';

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class,'vacancy_id');
    }
}
