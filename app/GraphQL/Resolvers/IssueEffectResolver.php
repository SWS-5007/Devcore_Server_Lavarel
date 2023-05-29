<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\IssueStatsLoader;
use App\Lib\GraphQL\GenericResolver;
use App\Models\Issue;
use App\Models\IssueEffect;
use App\Services\IssueEffectService;
use App\Services\IssueService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class IssueEffectResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('improve/idea', IssueEffect::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return IssueEffectService::instance();
    }

    public function changeStatus($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->changeStatus($args['input']);
    }

    public function tryDelete($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->tryDelete($args['id']);
    }

}
