<?php


namespace App\Support\Websockets\Server\Channels;

use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Subscriptions\Contracts\AuthorizesSubscriptions;
use Ratchet\ConnectionInterface;
use Nuwave\Lighthouse\Subscriptions\Contracts\StoresSubscriptions;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PrivateChannel;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\User;

class LighthouseSubscriptionAuthorizer
{

    public function __construct(AuthorizesSubscriptions $subscriptionAuthorizer)
    {
        $this->subscriptionAuthorizer = $subscriptionAuthorizer;
    }

    public function join(User $user)
    {
        return $this->subscriptionAuthorizer->authorize(request());

    //    return $user->id !== null;
       // return $this->subscriptionAuthorizer->authorize(request());
    }
}

