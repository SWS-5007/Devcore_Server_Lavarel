<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\CompanyRole;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class CompanyRoleValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'name' => ['string', 'required', 'min:3', new UniqueInCompanyRule(CompanyRole::query(), $entity ? ['id' => $entity->id] : null)],
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
