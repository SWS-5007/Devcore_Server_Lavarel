<?php

namespace App\Lib\Services;

use App\Lib\Models\Permissions\Permission;
use Illuminate\Support\Facades\Artisan;

class PermissionService extends GenericService
{
    private static $instance = null;
    protected $primaryKeyName = 'id';

    protected function __construct()
    {
        parent::__construct(Permission::class);
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function truncate()
    {
        Permission::truncate();
        Artisan::call("permission:cache-reset");
    }



    public function registerOperations($operations, $guard = 'web')
    {

        $ret = [];

        if (is_array($operations)) {
            foreach ($operations as $op) {
                $ret[] = $this->saveOperation($op, $guard);
            }
        } else {
            $ret[] = $this->saveOperation($operations, $guard);
        }
        return $ret;
    }

    private function saveOperation($name, $guard)
    {

        return Permission::findOrCreate($name, $guard);
    }
}
