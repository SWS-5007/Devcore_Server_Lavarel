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
use App\Models\ProjectEvaluationInstance;
use App\Models\ProjectEvaluationRecord;
use App\Validators\Rules\ExistsInCompanyRule;
use Illuminate\Validation\Rule;

class ProjectEvaluationRecordValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
            // 'project_id' => ['required', new ExistsInCompanyRule(Project::query())],
            // 'process_id' => ['required', new ExistsInCompanyRule(Process::query())],
            // 'evaluation_instance_id' => ['required', new ExistsInCompanyRule(ProjectEvaluationInstance::query(), ['project_id' => $col->get('project_id'), 'status' => 'OPEN'])],
            'skipped' => ['boolean', 'required'],
            'money_unit' => ['nullable', 'in:' . (join(',', ProjectEvaluationRecord::DIMENSIONS))],
            'money_value' => ['nullable', 'integer'],
            'time_unit' => ['nullable', 'in:' . (join(',', ProjectEvaluationRecord::DIMENSIONS))],
            'time_value' => ['nullable', 'integer'],
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
