<?php

namespace App\Services;

use App\Lib\Models\Permissions\Role;
use App\Lib\Services\GenericService;

class RoleService extends GenericService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Role::class, false);
    }

    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }
}