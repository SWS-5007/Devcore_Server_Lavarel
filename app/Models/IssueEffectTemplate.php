<?php

namespace App\Models;

use App\Lib\Models\HasPropertiesColumnTrait;

class IssueEffectTemplate extends BaseModel
{
    use HasPropertiesColumnTrait, HasCompanyTrait;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
    protected $fillable = ['effect_id','company_role_id','effect_value','effect_time','process_id','parent_type','parent_id'];

    public function parent()
    {
        return $this->morphTo('parent', 'parent_type', 'parent_id', 'id');
    }

    public function effect()
    {
        return $this->belongsTo(IssueEffect::class, 'id', 'effect_id');
    }

}
