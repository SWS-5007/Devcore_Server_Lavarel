<?php

namespace App\Services;

use App\Lib\Services\GenericService;
use App\Models\Industry;

class IndustryService extends GenericService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Industry::class, false);
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
        $instance->name = $data->get('name', $instance->name);
        return $instance;
    }
}
