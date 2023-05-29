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

class IssueEffectValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
//        'title' => ['string', 'required', 'min:3', Rule::unique('issue_effects', 'title')->where(function ($q) use ($entity, $data, $col) {
//            $q->where('process_id', $col->get('process_id'));
//            if ($col->get('stage_id') && !$col->get('operation_id')) {
//                $q->where('parent_id', $col->get('stage_id'));
//            }
//            if ($col->get('operation_id') && !$col->get('phase_id')) {
//                $q->where('parent_id', $col->get('operation_id'));
//            }
//            if ($col->get('phase_id')) {
//                $q->where('parent_id', $col->get('phase_id'));
//            }
//            if ($entity->getKey()) {
//                $q->where('id', '!=', $entity->getKey());
//            }
//        })],
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
