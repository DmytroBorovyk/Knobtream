<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'setting',
        'value'
    ];

    public $keyType = 'string';
}
