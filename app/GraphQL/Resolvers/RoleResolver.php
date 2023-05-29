<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Lib\Models\Permissions\Role;
use App\Lib\Services\RoleService;

class RoleResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('auth/role', Role::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return RoleService::instance();
    }

    public function listAllRoles(){
        $result = RoleService::instance()->find()->where('name','!=','Super Admin')->get();
        return $result;
    }
}
