<?php

namespace App\Models;

use App\Lib\Context;
use App\Lib\Models\HasPropertiesColumnTrait;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShareLink extends Model
{
    use HasPropertiesColumnTrait, HasCompanyTrait;

    protected $primaryKey = 'id';

    protected $table = 'project_sharelinks';

        protected $casts = [
            'company_role_ids' => 'array'
        ];
}
