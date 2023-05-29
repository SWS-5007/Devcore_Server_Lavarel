<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\CompanyTool;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class CompanyToolPriceValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
            'company_tool_id' => ['nullable', new ExistsInCompanyRule(CompanyTool::query())],
            'yearly_costs' => ['nullable', 'integer', 'min:1'],
            'price_model' => ['nullable', 'string'],
            'parent_id'=> ['nullable', 'required_if:price_model,PROJECT']
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}


