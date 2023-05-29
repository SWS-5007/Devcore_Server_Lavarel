<?php

namespace App\Services;

use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use App\Lib\Models\Resources\Resource;
use App\Models\HasCompanyTrait;
use App\Models\HasUserTrait;
use App\Models\IssueEffect;
use App\Models\IssueEffectTemplate;
use App\Validators\IssueEffectTemplateValidator;
use App\Validators\IssueEffectValidator;
use App\Validators\ProcessValidator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IssueEffectTemplateService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(IssueEffectTemplate::class, false);
    }


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    protected function beforeUpdate($data, $object)
    {
        return $object;
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);

        $instance->effect_id = $data->get('effect_id', $instance->effect_id);
        $instance->process_id = $data->get('process_id', $instance->process_id);

        $instance->effect_time = $data->get('effect_time', $instance->effect_time);
        $instance->effect_value = $data->get('effect_value', $instance->effect_value);

        $instance->company_role_id = $data->get('company_role_id', $instance->company_role_id);
        //parent
        if (!$data->get('operation_id')) {
            $instance->parent_type = "process_stage";
            $instance->parent_id = $data->get('stage_id');
        } else if (!$data->get('phase_id')) {
            $instance->parent_type = "process_operation";
            $instance->parent_id = $data->get('operation_id');
        } else {
            $instance->parent_type = "process_phase";
            $instance->parent_id = $data->get('phase_id');
        }
        $parent = $instance->parent;
        if (!$parent) {
            throw new NotFoundHttpException();
        }

        return $instance;
    }

    protected function getValidator($data, $object, $option)
    {
        return new IssueEffectTemplateValidator($data, $object, $option);
    }
    protected function deleted($object){
        return $object;
    }

    public function deleteMany($data)
    {
        $input = collect($data['ids']);
        $input->map(function ($id) {
            $this->delete($id);
        });
    }

}
