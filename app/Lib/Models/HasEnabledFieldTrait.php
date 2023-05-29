<?php

namespace App\Lib\Models;


use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;

trait HasEnabledFieldTrait
{
    protected static function bootHasEnabledFieldTrait()
    { }

    public function scopeEnabled(Builder $builder, $enabled = true)
    {
        $builder->where(self::getEnabledField(), true);
    }

    protected static function getEnabledField()
    {
        if (property_exists(self::class, 'enabledField')) {
            return self::$enabledField;
        }
        return 'enabled';
    }

    public function isEnabled()
    {
        return $this->attributes[self::getEnabledField()];
    }
}
