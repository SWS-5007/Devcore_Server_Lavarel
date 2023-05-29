<?php

namespace App\Services;

use App\Lib\Services\GenericService;
use App\UserDevice;

class UserDeviceService extends GenericService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(UserDevice::class, false);
    }

    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    protected function fillFromArray($option, $data, $instance)
    {

        $data = collect($data);
        $instance->token = $data->get('token', $instance->token);
        $instance->type = $data->get('type', $instance->type);
        $instance->user_id = $data->get('user_id', $instance->user_id);
        return $instance;
    }
}
