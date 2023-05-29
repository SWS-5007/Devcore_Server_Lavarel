<?php

namespace App\GraphQL\Subscriptions;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserUpdated extends GraphQLSubscription
{

    public function authorize(Subscriber $subscriber, Request $request): bool
    {
//        Log::info("USER AUTHORIZE",[$subscriber->args['author']]);
//        $user = $subscriber->context->user;
//        $author = User::find($subscriber->args['author']);

       // Log::info(json_encode($subscriber));
        return true;

    }

    public function filter(Subscriber $subscriber, $root): bool
    {
        $user = $subscriber->context->user;
        $authorized = true;


        if ($user) {
           // $authorized = ($user->id === $root->id);
            $authorized = ($user->id == $root->payload->id);
        }
        return $authorized;
    }

    public function resolve($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): User
    {

        return $root->payload;
    }
}
