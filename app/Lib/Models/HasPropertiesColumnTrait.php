<?php

namespace App\Lib\Models;

use Illuminate\Support\Arr;

trait HasPropertiesColumnTrait
{

    public static function bootHasPropertiesColumnTrait()
    {
    }

    //add properties for cast to array
    public function getCasts(): array
    {
        return array_merge(
            parent::getCasts(),
            [static::propertiesColumnName() => 'array']
        );
    }

    public function isPropertyDirty($key)
    {
        return $this->getOriginalProperty($key) != $this->getProperty($key);
    }

    public function getOriginalProperty($key, $defaultValue = null)
    {
        $properties = isset($this->original[static::propertiesColumnName()]) ? $this->original[static::propertiesColumnName()] : [];
        return Arr::get($properties, $key, $defaultValue);
    }

    public function getProperty($key, $defaultValue = null)
    {
        return Arr::get($this->{static::propertiesColumnName()}, $key, $defaultValue);
    }

    public function setProperty($key, $value = null)
    {
        $originalArr = $this->{static::propertiesColumnName()};
        if (!is_array($originalArr)) {
            $originalArr = [];
        }
        Arr::set($originalArr, $key, $value);

        $this->{static::propertiesColumnName()} = $originalArr;
        return $this->{static::propertiesColumnName()};
    }

    public function deleteProperty($key)
    {
        $originalArr = $this->{static::propertiesColumnName()};
        Arr::forget($originalArr, $key);
        if (!count($originalArr)) {
            $originalArr = null;
        }
        $this->{static::propertiesColumnName()} = $originalArr;
        return $this->{static::propertiesColumnName()};
    }


    public static function propertiesColumnName()
    {
        if (property_exists(self::class, 'propertiesColumnName')) {
            return static::$propertiesColumnName;
        }
        return 'properties';
    }
}
