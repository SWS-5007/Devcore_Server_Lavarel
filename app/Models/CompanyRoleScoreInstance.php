<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class CompanyRoleScoreInstance extends Model
{
    use HasCompanyTrait;

    protected $guarded = ['id'];

    public function companyRole()
    {
        return $this->belongsTo(CompanyRole::class, 'company_role_id', 'id');
    }
}

