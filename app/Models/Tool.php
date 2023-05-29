<?php

namespace App\Models;

use App\Lib\Models\HasTranslationsColumnTrait;

class Tool extends BaseModel
{
    use HasTranslationsColumnTrait;
    const TOOL_TYPES = [
        'TOOL',
        'MODULE',
    ];
    protected static $translationsFields = [
        'name'
    ];

    public function parent(){
        return $this->belongsTo(Tool::class, 'tool_id', 'id');
    }
}
