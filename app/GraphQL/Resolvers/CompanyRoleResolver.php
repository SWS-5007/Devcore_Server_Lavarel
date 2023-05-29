<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\CompanyRole;
use App\Services\CompanyRoleService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;


class CompanyRoleResolver extends GenericResolver
{

    public function __construct()
    {
        parent::__construct('core/company_role', CompanyRole::class, 'id', false);
    }

    protected function createServiceInstance()
    {
        return CompanyRoleService::instance();
    }

}
