<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Process;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class ProcessStageValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'title' => ['string', 'required', 'min:3', Rule::unique('process_stages', 'title')->where(function ($q) use ($data, $entity) {
                $q->where('process_id', $data['process_id']);
                if ($entity->getKey()) {
                    $q->where('id', '!=', $entity->getKey());
                }
            })],
            'process_id' => ['required', new ExistsInCompanyRule(Process::query())],
            'description' => 'nullable|string'
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
