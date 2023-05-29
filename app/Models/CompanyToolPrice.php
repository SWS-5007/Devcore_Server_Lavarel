<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyToolPrice extends Model
{
    use HasCompanyTrait;

    const STATUS = [
        'ACTIVE',
        'INACTIVE',
    ];

    const PRICE_MODELS = [
        'PROJECT',
        'LICENSE',
        'ONE_TIME_PAYMENT'
    ];

    const PRICE_INTERVALS = [
        'MONTH',
        'YEAR'
    ];


    protected $dates = ['expiration'];
    public $with = ['tool'];
    public $appends = ['name', 'type', 'yearly_costs'];

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id', 'id');
    }

    public function companyTool()
    {
        return $this->belongsTo(CompanyTool::class, 'parent_id', 'id');
    }

    public function getNameAttribute()
    {
        return $this->attributes['name'];
    }

    public function getTypeAttribute()
    {
        return '';
    }

    public function getYearlyCostsAttribute()
    {
        return $this->attributes['price'];
    }

    public function parent()
    {
        return $this->belongsTo(Project::class, 'parent_id', 'id');
    }
}
