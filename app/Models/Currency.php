<?php

namespace App\Models;

use App\Lib\Models\CachableModel;

class Currency extends CachableModel
{
    public $incrementing = false;
    public $primaryKey = "code";
}
