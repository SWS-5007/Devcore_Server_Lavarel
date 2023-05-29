<?php

namespace App\Services;

use App\Lib\GraphQL\Exceptions\BadRequestException;
use App\Lib\Services\GenericService;
use App\Models\CompanyRole;
use App\Validators\CompanyRoleValidator;

class CompanyRoleService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(CompanyRole::class, false);
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
        return new CompanyRoleValidator($data, $object, $option);
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $instance = parent::fillFromArray($option, $data, $instance);
        $data = collect($data);
        $instance->name = $data->get('name', $instance->name);
        $instance->company_id = $data->get('company_id', $instance->company_id);
        return $instance;
    }

    public function deleted($object){
        return $object;
    }

    protected function beforeDelete($object)
    {
        if ($object->users && $object->users->isNotEmpty()) {
            throw new BadRequestException(__('You cannot delete a role that contains users!'));
        }
        return $object;
    }
}
