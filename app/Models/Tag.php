<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    protected $table = 'tags';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'title'
    ];

    public $keyType = 'string';
}
