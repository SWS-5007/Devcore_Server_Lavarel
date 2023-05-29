<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\IdeaIssueStatsLoader;
use App\Lib\GraphQL\GenericResolver;
use App\Models\IdeaIssue;
use App\Services\IdeaIssueService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class IdeaIssueResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('improve/idea', IdeaIssue::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return IdeaIssueService::instance();
    }

    public function changeStatus($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->changeStatus($args['input']);
    }

}
