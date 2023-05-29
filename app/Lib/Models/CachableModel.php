<?php

namespace App\Lib\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class CachableModel extends BaseModel
{
    use Cachable;
}
