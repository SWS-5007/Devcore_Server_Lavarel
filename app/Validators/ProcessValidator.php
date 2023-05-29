<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Process;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class ProcessValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'title' => ['string', 'required', 'min:3', new UniqueInCompanyRule(Process::query(), $entity ? ['id' => $entity->id] : null)],
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}