<?php

namespace App\Models;

use App\Lib\Models\HasPropertiesColumnTrait;

class ModelHasCompanyTool extends BaseModel
{
    use HasPropertiesColumnTrait;

    function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id', 'id');
    }

    function tool(){
        return $this->belongsTo(CompanyTool::class, 'company_tool_id', 'id');
    }
}
