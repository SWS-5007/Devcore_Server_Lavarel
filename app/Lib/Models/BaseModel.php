<?php

namespace App\Lib\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model
{
    //use ClearsResponseCacheTrait;

    //adds disable cache scope fake
    public function scopeDisableCache(Builder $builder)
    {
        return $builder;
    }
}
