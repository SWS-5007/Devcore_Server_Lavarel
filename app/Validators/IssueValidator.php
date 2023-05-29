<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Issue;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessPhase;
use App\Models\ProcessStage;
use App\Models\ProjectStage;
use App\Models\Project;
use App\Validators\Rules\ExistsInCompanyRule;
use Illuminate\Validation\Rule;

class IssueValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
            'source_id' => ['nullable', new ExistsInCompanyRule(Issue::query())],
            'project_id' => ['required', new ExistsInCompanyRule(Project::query())],
            'process_id' => ['required', new ExistsInCompanyRule(Process::query())],
            'stage_id' => ['required', new ExistsInCompanyRule(ProcessStage::query(), ['process_id' => $col->get('process_id')])],
            'operation_id' => ['nullable', new ExistsInCompanyRule(ProcessOperation::query(), ['stage_id' => $col->get('stage_id')])],
            'phase_id' => ['nullable', new ExistsInCompanyRule(ProcessPhase::query(), ['operation_id' => $col->get('operation_id')])],
            'type' => ['required', 'in:' . (join(',', Issue::TYPES))],
            'money_unit' => ['nullable', 'in:' . (join(',', Issue::DIMENSIONS))],
            'money_value' => ['nullable', 'integer'],
            'time_unit' => ['nullable', 'in:' . (join(',', Issue::DIMENSIONS))],
            'time_value' => ['nullable', 'integer'],
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
