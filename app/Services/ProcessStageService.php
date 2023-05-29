<?php

namespace App\Services;

use App\Models\Idea;
use App\Models\ProcessStage;
use App\Models\Project;
use App\Validators\ProcessStageValidator;
use App\Validators\Rules\ExistsInCompanyRule;
use Illuminate\Support\Facades\DB;

class ProcessStageService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(ProcessStage::class, false);
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
        return new ProcessStageValidator($data, $object, $option);
    }

    protected function syncRoles($data, $instance)
    {
        $data = collect($data);
        $instance->syncCompanyRoles($data->get('company_roles', []));
        return $instance;
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        $instance->title = $data->get('title', $instance->title);
        $instance->description = $data->get('description', $instance->description);
        $instance->process_id = $data->get('process_id', $instance->process_id);
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
                'process_id' => ['required', new ExistsInCompanyRule(ProcessService::instance()->find())],
                'd_order' => 'required|integer|min:1'
            ]
        )->validate();

        DB::beginTransaction();
        $data = collect($data);
        $entity->process_id = (int) $data->get('process_id', $entity->process_id);
        $entity->d_order = (int) $data->get('d_order', $entity->d_order);
        $entity->save();
        DB::commit();
        return $entity;
    }
}
