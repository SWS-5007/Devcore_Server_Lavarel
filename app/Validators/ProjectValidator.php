<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Process;
use App\Models\Project;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class ProjectValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $rules = [
            'process_id' => ['required', new ExistsInCompanyRule(Process::query())],
            'name' => ['string', 'required', 'min:3', Rule::unique('projects', 'name')->where(function ($q) use ($data, $entity) {
                $q->where('process_id', $data['process_id']);
                if ($entity->getKey()) {
                    $q->where('id', '!=', $entity->getKey());
                }
            })],
            'description' => 'nullable|string',
            'idea_ids' => 'nullable|array|min:1',
            'company_tool_ids' => 'nullable|array|min:1',
            'user_ids' => 'required|array|min:1',
            'budget' => 'required|string',
            'type' => 'required|in:'.join(',', Project::PROJECT_TYPES),
            'evaluation_type' => 'required|in:' . join(',', Project::EVALUATION_TYPES),
            'evaluation_interval_unit' => 'nullable|required_if:evaluation_type,PERIODIC',
            'evaluation_interval_amount' => 'nullable|integer|min:1|required_if:evaluation_type,PERIODIC',
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
