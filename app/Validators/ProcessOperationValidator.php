<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Process;
use App\Models\ProcessStage;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class ProcessOperationValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'title' => ['string', 'required', 'min:3', Rule::unique('process_operations', 'title')->where(function ($q) use ($data, $entity) {
                $q->where('stage_id', $data['stage_id']);
                if ($entity->getKey()) {
                    $q->where('id', '!=', $entity->getKey());
                }
            })],
            'stage_id' => ['required', new ExistsInCompanyRule(ProcessStage::query())],
            'description' => 'nullable|string'
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
