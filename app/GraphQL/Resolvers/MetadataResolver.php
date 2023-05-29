<?php

namespace App\GraphQL\Resolvers;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MetadataResolver
{
    public function getUser()
    {
        return Auth::user();
    }

    public function getObjectMetadata($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $ret = [
            'permissions' => []
        ];

        $permissionNames = ['create', 'update', 'delete'];
        $args = collect($args);
        $basePermissionName = $args->get('module', 'core/');
        if ($rootValue) {
            if($rootValue->_metadata){
                $ret=array_merge($ret, $rootValue->_metadata);
            }


            if (method_exists('module', $rootValue)) {
                $basePermissionName = $rootValue->module();
            }
            if (method_exists('declaredPermissions', $rootValue)) {
                $permissionNames = $rootValue->declaredPermissions();
            }
        }

        if ($args->has("permissions")) {
            $permissionNames = $args->get("permissions");
        }

        if (!is_array($permissionNames)) {
            $permissionNames = [$permissionNames];
        }

        if ($args->has("extraPermissions")) {
            $permissionNames = array_merge($permissionNames, $args->get("extraPermissions"));
        }

        foreach ($permissionNames as $p) {
            $completePermissionName = $basePermissionName . $p;
            if (Gate::forUser($this->getUser())->allows($completePermissionName, $rootValue)) {
                $ret['permissions'][] = $completePermissionName;
            }
        }

        return $ret;
    }
}
