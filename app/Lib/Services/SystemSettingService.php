<?php

namespace App\Lib\Services;

use App\Lib\Models\SystemSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SystemSettingService extends GenericService
{
    private static $instance = null;
    protected $primaryKeyName = 'id';
    protected $settings = null;

    protected function __construct()
    {
        parent::__construct(SystemSetting::class);
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function clearCache()
    {
        $this->settings = null;
        Cache::forget('system_settings');
    }

    public function getBaseQuery($with = [])
    {

        if (!$this->settings) {
            $this->settings = Cache::rememberForever('system_settings', function () {
                return SystemSetting::all();
            });
        }
        return $this->settings;
    }

    protected function updating($data, $object)
    {
        $this->clearCache();
    }

    protected function creating($data, $object)
    {
        $this->clearCache();
    }

    protected function deleting($object)
    {
        $this->clearCache();
    }

    public function get($key, $default = null)
    {
        $parts = explode('.', $key);
        $group = array_shift($parts);
        $key = implode('.', $parts);
        $setting = $this->getBaseQuery()->where('group', $group)->where('key', $key)->first();
        if (!$setting) {
            return $default;
        }
        return $setting->value['value'] ?? $default;
    }
}
