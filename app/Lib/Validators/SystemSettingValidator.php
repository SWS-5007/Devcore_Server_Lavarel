<?php

namespace App\Lib\Validators;


class SystemSettingValidator extends GenericValidator
{
    public function __construct($data, $action = '', $entity = null, $user = null)
    {

        $rules = [
            'key' => ['required', 'string'],
            
        ];

        $this->setRules($rules)
            ->setData($data)
            ->setAction($action)
            ->setEntity($entity)
            ->setUser($user);
    }
}