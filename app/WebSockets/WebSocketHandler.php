<?php

namespace App\WebSockets;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Subscriptions\Contracts\StoresSubscriptions as Storage;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class WebSocketHandler extends \BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface $connection)
    {
        logger(json_encode($connection->httpRequest->getHeaders()));
        parent::onOpen($connection);
    }


    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        parent::onError($connection, $e);
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $message)
    {

        if ($message->getPayload()) {
            $payload = json_decode($message->getPayload(), true);

            $eventName = Str::camel(Str::after(Arr::get($payload, 'event'), ':'));
            if ($eventName === 'unsubscribe') {
                $storage = app(Storage::class);

                $storage->deleteSubscriber(
                    Arr::get($payload, 'data.channel')
                );
            }
        }

        parent::onMessage($connection, $message);
    }
}
