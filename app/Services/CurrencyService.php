<?php

namespace App\Services;

use App\Lib\Services\GenericService;
use App\Models\Currency;

class CurrencyService extends GenericService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Currency::class, false);
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
        $instance->code = $data->get('code', $instance->code);
        $instance->name = $data->get('name', $instance->name);
        $instance->symbol = $data->get('symbol', $instance->symbol);
        return $instance;
    }
}
