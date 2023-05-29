<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class ShareLinkValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'project_id'    => ['required'],
            'url_name'      => ['required']
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
