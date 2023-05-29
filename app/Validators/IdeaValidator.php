<?php

namespace App\Validators;

use App\Lib\Validators\GenericValidator;
use App\Models\CompanyTool;
use App\Models\Idea;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessPhase;
use App\Models\ProcessStage;
use App\Validators\Rules\ExistsInCompanyRule;
use App\Validators\Rules\UniqueInCompanyRule;
use Illuminate\Validation\Rule;

class IdeaValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
            'title' => ['string', 'required', 'min:3', Rule::unique('ideas', 'title')->where(function ($q) use ($entity, $data, $col) {
                if ($col->get('source_id')) {
                   //$q->whereRaw("1!=1");
                } else {
                    $q->where('process_id', $col->get('process_id'));
                    if ($col->get('stage_id') && !$col->get('operation_id')) {
                        $q->where('parent_id', $col->get('stage_id'));
                    }
                    if ($col->get('operation_id') && !$col->get('phase_id')) {
                        $q->where('parent_id', $col->get('operation_id'));
                    }
                    if ($col->get('phase_id')) {
                        $q->where('parent_id', $col->get('phase_id'));
                    }
                    if ($entity->getKey()) {
                        $q->where('id', '!=', $entity->getKey());
                    }
                }
            })],
            'source_id' => ['nullable'/*, new ExistsInCompanyRule(Idea::query())*/],
            'process_id' => ['required', new ExistsInCompanyRule(Process::query())],
            'stage_id' => ['required', new ExistsInCompanyRule(ProcessStage::query(), ['process_id' => $col->get('process_id')])],
            'operation_id' => ['nullable', new ExistsInCompanyRule(ProcessOperation::query(), ['stage_id' => $col->get('stage_id')])],
            'phase_id' => ['nullable', new ExistsInCompanyRule(ProcessPhase::query(), ['operation_id' => $col->get('operation_id')])],
            'type' => ['required', 'in:' . (join(',', Idea::IDEA_TYPES))],
            'company_tool_id' => ['required_if:type,TOOL', new ExistsInCompanyRule(CompanyTool::query())]
            //'description' => 'required|string'
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
