<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\Milestone;
use App\Services\MilestoneService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MilestoneResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('core/support/engage', Milestone::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return MilestoneService::instance();
    }

    public function rewardMilestone($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->rewardMilestone($args['input']);
    }

    public function deleteMany($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->deleteMany($args);
    }

    public function updateOrDeleteMany($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->updateOrDeleteMany($args['input'], $context);
    }
}
