<?php

namespace App\Lib\Models;

class Notification
{
    public $type = 'generic';
    public $payload = null;

    public function __construct($type = 'generic', $payload = null)
    {
        $this->type = $type;
        $this->payload = $payload;
    }
}
