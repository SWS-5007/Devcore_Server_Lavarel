<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\BaseResolver;
use App\Services\ReportSerivce;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ReportResolver extends BaseResolver
{
    public function ideaReport($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        return ReportSerivce::instance()->ideaReport($args['filter']);
    }

    public function peopleReport($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {

        return ReportSerivce::instance()->peopleReport($args);
    }

    public function userProjectReport($root, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        return ReportSerivce::instance()->userProjectReport($args['filter']);
    }
}
