<?php

namespace App\Models;

use App\Lib\Models\HasPropertiesColumnTrait;

class ModelHasRole extends BaseModel
{
    use HasPropertiesColumnTrait;
    protected $primaryKey = 'model_id';
    function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id', 'role_id');
    }

}
