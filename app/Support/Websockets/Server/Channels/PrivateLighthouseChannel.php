<?php

namespace App\Support\Websockets\Server\Channels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Ratchet\ConnectionInterface;
use Nuwave\Lighthouse\Subscriptions\Contracts\StoresSubscriptions;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PrivateChannel;

class PrivateLighthouseChannel extends PrivateChannel
{
    public function unsubscribe(ConnectionInterface $connection): void
    {
        parent::unsubscribe($connection);

        if (Str::startsWith($this->channelName, 'private-lighthouse-') && !$this->hasConnections()) {
            static::lighthouseSubscriptionsStorage()->deleteSubscriber($this->channelName);
        }
    }

    private static function lighthouseSubscriptionsStorage(): StoresSubscriptions
    {

        return app(StoresSubscriptions::class);
    }
}
