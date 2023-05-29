<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\CompanyTool;
use App\Models\Tool;
use App\Services\CompanyToolService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CompanyToolResolver extends GenericResolver
{

    public function __construct()
    {
        parent::__construct('core/companyTool', CompanyTool::class, 'id', false);
    }

    protected function createServiceInstance()
    {
        return CompanyToolService::instance();
    }


    public function changeStatus($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        return $this->createServiceInstance()->changeStatus($args['input']);
    }

    public function updateTool($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $result = $this->createServiceInstance()->updateTool($args['input']);
        return $result;
    }


}
