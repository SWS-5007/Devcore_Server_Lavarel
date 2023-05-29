<?php

namespace App\Services;

use App\Models\ProcessOperation;
use App\Validators\ProcessOperationValidator;
use App\Validators\Rules\ExistsInCompanyRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessOperationService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(ProcessOperation::class, false);
    }

    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }
    protected function getValidator($data, $object, $option)
    {
        return new ProcessOperationValidator($data, $object, $option);
    }

    protected function syncRoles($data, $instance)
    {
        $input = collect($data);
        $companyRoles = $input->get('company_roles', []);

        $instance->syncCompanyRoles($companyRoles);
        return $instance;
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        $instance->title = $data->get('title', $instance->title);
        $instance->description = $data->get('description', $instance->description);
        $instance->stage_id = $data->get('stage_id', $instance->stage_id);
        $instance->process_id = ProcessStageService::instance()->findByPrimaryKey($instance->stage_id)->process_id;
        $instance->d_order = $data->get('d_order', $instance->d_order);
        return $instance;
    }

    protected function created($data, $object)
    {
        $object = $this->syncRoles($data, $object);
        return $object;
    }

    protected function updated($data, $object)
    {
        $object = $this->syncRoles($data, $object);
        return $object;
    }

    protected function deleted($object)
    {
        return $object;
    }


    public function updateDisplayOrder($entity, $data)
    {
        validator(
            $data,
            [
                'stage_id' => ['required', new ExistsInCompanyRule(ProcessStageService::instance()->find())],
                'd_order' => 'required|integer|min:1'
            ]
        )->validate();

        DB::beginTransaction();
        $data = collect($data);
        $entity->stage_id = (int) $data->get('stage_id', $entity->stage_id);
        $entity->d_order = (int) $data->get('d_order', $entity->d_order);
        $entity->save();
        DB::commit();
        return $entity;
    }
}
