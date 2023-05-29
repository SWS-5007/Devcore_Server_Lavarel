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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class IdeaContentValidator extends GenericValidator
{
    public function __construct($data = [], $entity = null, $action = '', $messages = [])
    {
        $col = collect($data);
        $rules = [
            'idea_id' => ['required'],
            'content_type' => ['string', 'required', 'min:4', Rule::unique('idea_contents', 'content_type')->where(function ($q) use ($entity, $data, $col) {

                $q->where('idea_id', $col->get('idea_id'))->whereNull('deleted_at');

                if ($col->get('id')) {
                    $q->where('idea_id', $col->get('idea_id'));
                }
                if ($entity->getKey()) {
                    $q->where('id', '!=', $entity->getKey());
                }

            })]
        ];
        parent::__construct($rules, $data, $entity, $action, $messages);
    }
}
