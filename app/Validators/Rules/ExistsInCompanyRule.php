<?php

namespace App\Validators\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class ExistsInCompanyRule implements Rule
{
    private $companyId;
    private $baseQuery;
    private $extraConditions;


    public function __construct(Builder $baseQuery, $extraConditions = null, $companyId = null)
    {
        $this->companyId = $companyId;
        $this->extraConditions = $extraConditions;
        if (!$this->companyId) {
            $this->companyId = $this->getUserCompanyId();
        }
        $this->baseQuery = $baseQuery;
    }


    protected function getUserCompanyId()
    {
        return config("app.company_id");
    }

    public function passes($attribute, $value)
    {
        $exists = false;

        if ($this->baseQuery && $this->companyId) {
            $q = $this->baseQuery->where('id', $value)->where('company_id', $this->companyId);
            if ($this->extraConditions) {
                $q->where(function ($q) {
                    foreach ($this->extraConditions as $key => $condition) {
                        $q->where($key, $condition);
                    }
                });
            }
            $exists = $q->first();
        } else {
            $exists = true;
        }

        return $exists;
    }

    public function message()
    {
        return trans()->get("validation.exists");
    }
}
