<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Milestone;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class MilestoneValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
            'company_id' => ['nullable', new ExistsInCompanyRule(Milestone::query())],
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
