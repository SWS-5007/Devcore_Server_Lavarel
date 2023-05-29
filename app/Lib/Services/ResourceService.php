<?php

namespace App\Lib\Services;

use App\Lib\Models\Resources\Resource;

class ResourceService extends GenericService
{
    private static $instance = null;
    protected $primaryKeyName = 'id';

    protected function __construct()
    {
        parent::__construct(Resource::class);
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    protected function updating($data, $object)
    {
        return $object;
    }

    protected function creating($data, $object)
    {
        $object->savefile();
        return $object;
    }


}
