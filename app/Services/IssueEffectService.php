<?php

namespace App\Services;

use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use App\Lib\Models\Resources\Resource;
use App\Models\HasCompanyTrait;
use App\Models\HasUserTrait;
use App\Models\IssueEffect;
use App\Models\IssueEffectTemplate;
use App\Validators\IssueEffectValidator;
use App\Validators\ProcessValidator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IssueEffectService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(IssueEffect::class, false);
    }

    protected function syncTemplates($data, $instance,$option)
    {
        $data = collect($data);
        $templates = $data->get('templates', $instance->templates);

        //Remove existing
        $instance->templates()->delete();

        if($templates){
        foreach ($templates as $template){
            $template['effect_id'] = $instance->id;

            if (!isset($template['operation_id'])) {
                $template['parent_type'] = "process_stage";
                $template['parent_id'] = $template['stage_id'];
            } else if (!isset($template['phase_id'])) {
                $template['parent_type'] = "process_operation";
                $template['parent_id'] = $template['operation_id'];
            } else {
                $template['parent_type'] = "process_phase";
                $template['parent_id'] = $template['phase_id'];
            }
               IssueEffectTemplate::create($template);
        }}
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
        $data = collect($data);
        return $object;
    }

    public function deleted($object)
    {

        $issue = $object->issue()->first();
        if($issue){
            $issue->effect_template_id = null;
            $issue->save();
        }
        return $object;
    }

    protected function created($data, $object)
    {
        $this->syncTemplates($data, $object, 'create');
    }

    protected function updated($data, $object)
    {
        $this->syncTemplates($data, $object, 'update');
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        $instance->title = $data->get('title', $instance->title);
        $instance->issue_active_id = $data->get('issue_active_id', $instance->issue_active_id);

        $instance->effect_time = $data->get('effect_time', $instance->effect_time);
        $instance->effect_value = $data->get('effect_value', $instance->effect_value);


        return $instance;
    }

     public function tryDelete($data)
    {
        $test= $data;

        return $data;
    }

    protected function getValidator($data, $object, $option)
    {
        return new IssueEffectValidator($data, $object, $option);
    }
}
