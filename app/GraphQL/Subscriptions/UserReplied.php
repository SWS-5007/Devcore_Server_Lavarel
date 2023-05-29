<?php


namespace App\GraphQL\Subscriptions;

use App\Models\IdeaIssueReply;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Log;

class UserReplied extends GraphQLSubscription
{

    /**
     * Check if subscriber is allowed to listen to the subscription.
     *
     * @param \Nuwave\Lighthouse\Subscriptions\Subscriber $subscriber
     * @param \Illuminate\Http\Request $request
     * @return bool
     */

    public function authorize(Subscriber $subscriber, Request $request): bool
    {
        return true;
    }

    /**
     * Filter which subscribers should receive the subscription.
     *
     * @param \Nuwave\Lighthouse\Subscriptions\Subscriber $subscriber
     * @param mixed $root
     * @return bool
     */
    public function filter(Subscriber $subscriber, $root): bool
    {
       // Log::info(json_encode($root));
        $user = $subscriber->context->user;

        $authorized = true;
        if ($user) {
            $authorized = ($user->id == $root->payload->id);
        }
        return $authorized;
    }

    /**
     * Resolve the subscription.
     *
     * @param \App\Models\Project $root
     * @param mixed[] $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo
     * @return mixed
     */
    public function resolve($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): IdeaIssueReply
    {

        unset($root->payload->author);
        return $root->payload;
    }
}
