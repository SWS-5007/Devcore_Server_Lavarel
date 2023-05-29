<?php

namespace App\Lib\Services;

use App\Lib\Models\Permissions\Role;

class RoleService extends GenericService
{
    private static $instance = null;
    protected $primaryKeyName = 'id';

    protected function __construct()
    {
        parent::__construct(Role::class);
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function fillFromArray($option, $data, $instance)
    {
        
    }

}