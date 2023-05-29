<?php

namespace App\Validators\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class UniqueInCompanyRule implements Rule
{
    private $companyId;
    private $baseQuery;
    private $ignoreParams;


    public function __construct(Builder $baseQuery, $ignoreParams = null, $companyId = null)
    {
        $this->companyId = $companyId;
        $this->ignoreParams = $ignoreParams;
        if (!$this->companyId) {
            $this->companyId = $this->getUserCompanyId();
        }
        $this->baseQuery = $baseQuery;
    }

    protected function getUser()
    {
        return auth('api')->user();
    }

    protected function getUserCompanyId()
    {
        return config("app.company_id");
    }

    public function passes($attribute, $value)
    {
        $exists = false;

        if ($this->baseQuery && $this->companyId) {
            $q = $this->baseQuery->where($attribute, $value)->where('company_id', $this->companyId);
            if ($this->ignoreParams) {
                $q->where(function ($q) {
                    foreach ($this->ignoreParams as $key => $value) {
                        $q->where($key, '!=', $value);
                    }
                });
            }
            $exists = $q->first();
        }

        return !$exists;
    }

    public function message()
    {
        return trans()->get("validation.unique");
    }
}
