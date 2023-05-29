<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\CompanyTool;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessPhase;
use App\Models\ProcessStage;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class UserValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|unique:users,email,' . $entity->id,
            'yearly_costs' => ['nullable', 'integer'],
            'company_role_id' => 'nullable',
            'role_id' => 'nullable',
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
