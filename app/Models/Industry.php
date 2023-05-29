<?php
namespace App\Models;

use App\Lib\Models\CachableModel;
use App\Lib\Models\HasTranslationsColumnTrait;

class Industry extends CachableModel{
    use HasTranslationsColumnTrait;
    protected static $translationsFields = [
        'name'
    ];
}