<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\IssueStatsLoader;
use App\Lib\GraphQL\GenericResolver;
use App\Models\Issue;
use App\Services\IssueService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class IssueResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('improve/idea', Issue::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return IssueService::instance();
    }

    public function changeStatus($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->changeStatus($args['input']);
    }

    public function checkIssue($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->checkIssue($args['input']);
    }

    public function setTemplate($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->setTemplate($args['input']);
    }
    public function unsetTemplate($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->setTemplate($args['input']);
    }

    public function closeIssueFeedback($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->closeIssueFeedback($args);
    }

    public function deleteMany($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->deleteMany($args);
    }

}
