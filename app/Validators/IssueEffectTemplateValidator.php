<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\Issue;
use App\Models\IssueEffect;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessPhase;
use App\Models\ProcessStage;
use App\Models\ProjectStage;
use App\Models\Project;
use App\Validators\Rules\ExistsInCompanyRule;
use Illuminate\Validation\Rule;

class IssueEffectTemplateValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
            'company_role_id' => ['required'],
            'effect_id' => ['required'],
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
