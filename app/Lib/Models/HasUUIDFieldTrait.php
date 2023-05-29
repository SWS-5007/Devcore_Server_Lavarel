<?php

namespace App\Lib\Models;

use Illuminate\Support\Str;

trait HasUUIDFieldTrait
{
    protected static function bootHasUUIDFieldTrait()
    {
        /*static::retrieved(function($model){
            if (!$model->{$model::getUUIDFieldName()}) {
                $model->{$model::getUUIDFieldName()} = (string) Str::uuid();
                $model->save();
            }
        });*/

        static::saving(function ($model) {
            if (!$model->{$model::getUUIDFieldName()}) {
                $model->{$model::getUUIDFieldName()} = (string) Str::uuid();
            }
        });
    }

    public static function getUUIDFieldName()
    {
        if (property_exists(self::class, 'uuidFieldName')) {
            return self::$uuidFieldName;
        }
        return 'uuid';
    }

    public function getUUID()
    {
        if (!$this->{$this::getUUIDFieldName()}) {
            $this->{$this::getUUIDFieldName()} = (string) Str::uuid();
            if($this->exists){
                $this->save();
            }
           
        }
        return  $this->{$this::getUUIDFieldName()};
    }

    public function setUUID($value)
    {
        $this->{$this::getUUIDFieldName()} = $value;
        return $this;
    }
}
