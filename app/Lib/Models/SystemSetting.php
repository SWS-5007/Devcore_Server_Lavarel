<?php

namespace App\Lib\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $casts = [
        'value' => 'array',
    ];
}
