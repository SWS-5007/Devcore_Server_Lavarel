<?php

namespace App\Models;

use App\Lib\Models\HasPropertiesColumnTrait;

class ModelHasCompanyRole extends BaseModel
{
    use HasPropertiesColumnTrait;

    function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id', 'id');
    }

    function companyRole(){
        return $this->belongsTo(CompanyRole::class, 'company_role_id', 'id');
    }

    
    
}
