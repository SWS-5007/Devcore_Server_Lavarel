<?php

namespace App\GraphQL\Mutations;

use App\Events\NewMessage;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\Utils\Subscription;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class TriggerTestSubscriptionMutation
{
    public function resolve($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
       //$proj = Project::find(1);
        $proj = Project::find(1);
        \Nuwave\Lighthouse\Execution\Utils\Subscription::broadcast('projectUpdated', $proj);

        return true;
    }
}
