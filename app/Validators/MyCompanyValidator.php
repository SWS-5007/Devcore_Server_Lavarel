<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Lib\Validators\Rules\TwilioPhoneValidator;

class MyCompanyValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'name' => 'required|string|min:4|unique:companies,name' . ($entity->id ? ',' . $entity->id . ',id' : ''),
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'currency_code' => 'required|string|exists:currencies,code',
        ];

        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
