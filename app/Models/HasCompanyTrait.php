<?php

namespace App\Models;

use App\Lib\Context;
use Illuminate\Database\Eloquent\Builder;

trait HasCompanyTrait
{
    protected static function bootHasCompanyTrait()
    {
        static::addGlobalScope('only_current_company', function ($builder) {
            if (Context::get()->getCompanyId()) {
                return $builder->where(with(new static)->getTable() . '.company_id', Context::get()->getCompanyId());
            }
            return $builder;
        });

        static::saving(function ($model) {
            if (!$model->company_id && !$model->is_super_admin) {
                $model->company_id = Context::get()->getCompanyId();
            }
        });
    }

    public function scopeWithOtherCompanies(Builder $builder)
    {
        $builder->withoutGlobalScope('only_current_company');
    }

    public function scopeCurrentCompany(Builder $builder, $companyId = null)
    {
        return $builder->where('company_id', ($companyId ? $companyId : Context::get()->getCompanyId()));
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
