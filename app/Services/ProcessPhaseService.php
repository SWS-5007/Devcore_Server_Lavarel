<?php

namespace App\Services;

use App\Models\ProcessPhase;
use App\Validators\Rules\ExistsInCompanyRule;
use Illuminate\Support\Facades\DB;

class ProcessPhaseService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(ProcessPhase::class, false);
    }

    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
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
        $instance->operation_id = $data->get('operation_id', $instance->operation_id);
        $instance->process_id= ProcessOperationService::instance()->findByPrimaryKey($instance->operation_id)->process_id;
        $instance->d_order = $data->get('d_order', $instance->d_order);
        return $instance;
    }

    protected function deleted($object)
    {
        return $object;
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

    public function updateDisplayOrder($entity, $data)
    {
        validator(
            $data,
            [
                'operation_id' => ['required', new ExistsInCompanyRule(ProcessOperationService::instance()->find())],
                'd_order' => 'required|integer|min:1'
            ]
        )->validate();

        DB::beginTransaction();
        $data = collect($data);
        $entity->operation_id = (int) $data->get('operation_id', $entity->operation_id);
        $entity->d_order = (int) $data->get('d_order', $entity->d_order);
        $entity->save();
        DB::commit();
        return $entity;
    }
}
