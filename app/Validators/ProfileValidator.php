<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Lib\Validators\Rules\TwilioPhoneValidator;
use Illuminate\Support\Facades\Log;

class ProfileValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'first_name' => 'nullable|string|min:2',
            'last_name' => 'nullable|string|min:2',
            'lang' => 'nullable|string|in:' . join(',',config("app.available_locales")),
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'email' => 'email|unique:users,email,' . $entity->id,
            'phone' => ['unique:users,phone,' . $entity->id, new TwilioPhoneValidator],
            'change_password' => 'nullable|boolean',

        ];
        if ($data['change_password']) {
            $rules['password'] = 'required|confirmed|min:6';
        }

        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
