<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class ProcessPhaseValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'title' => ['string', 'required', 'min:3', Rule::unique('process_phases', 'title')->where(function ($q) use ($data, $entity) {
                $q->where('operation_id', $data['operation_id']);
                if ($entity->getKey()) {
                    $q->where('id', '!=', $entity->getKey());
                }
            })],
            'operation_id' => ['required', new ExistsInCompanyRule(ProcessOperation::query())],
            'description' => 'nullable|string'
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
