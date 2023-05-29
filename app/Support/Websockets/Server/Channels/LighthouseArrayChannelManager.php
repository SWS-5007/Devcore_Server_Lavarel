<?php

namespace App\Support\Websockets\Server\Channels;

use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManagers\ArrayChannelManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LighthouseArrayChannelManager extends ArrayChannelManager
{
    protected function determineChannelClass(string $channelName): string
    {
        if (Str::startsWith($channelName, 'private-lighthouse-')) {
            return PrivateLighthouseChannel::class;
        }

        return parent::determineChannelClass($channelName);
    }
}
