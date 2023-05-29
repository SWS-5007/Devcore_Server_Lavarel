<?php

namespace App\Services;

use App\Models\Process;
use App\Validators\ProcessValidator;

class ProcessService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Process::class, false);
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
        $instance->title = $data->get('title', $instance->title);
        return $instance;
    }

    protected function getValidator($data, $object, $option)
    {
        return new ProcessValidator($data, $object, $option);
    }

    public function deleted($object){
         return $object;
    }
}
