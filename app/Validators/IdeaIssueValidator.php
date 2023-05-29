<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Idea;
use App\Models\IdeaIssue;
use App\Models\Process;
use App\Models\ProjectStage;
use App\Models\Project;
use App\Models\ProjectIdea;
use App\Validators\Rules\ExistsInCompanyRule;
use Illuminate\Validation\Rule;

class IdeaIssueValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
            'title' => ['string', 'nullable'],
            'project_id' => ['required', new ExistsInCompanyRule(Project::query())],
            'process_id' => ['required', new ExistsInCompanyRule(Process::query())],
            'stage_id' => ['nullable', Rule::exists('project_stages', 'id')->where('project_id', $col->get('project_id'))],
            'type' => ['required', 'in:' . (join(',', IdeaIssue::TYPES))],
            'parent_id' => ['required', new ExistsInCompanyRule(ProjectIdea::query())]
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
