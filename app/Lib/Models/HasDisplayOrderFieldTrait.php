<?php

namespace App\Lib\Models;


use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasDisplayOrderFieldTrait
{
    protected static function bootHasDisplayOrderFieldTrait()
    {

        static::saved(function ($model) {
            if ($model->wasRecentlyCreated === true || $model->isDirty(static::getDisplayOrderField())) {
                $model->preventDisplayOrderGasps();
            }
        });

        static::creating(function ($model) {
            if ($model->{static::getDisplayOrderField()} === null) {
                $oldOrder = 0;
                $newOrder = $model->getMaxDisplayOrderField() + 1;
                $model->setDisplayOrder($newOrder, $oldOrder);
            } else {
                $newOrder = $model->{static::getDisplayOrderField()};
                $oldOrder = 0;
                $model->setDisplayOrder($newOrder, $oldOrder);
            }
        });
        static::updating(function ($model) {
            if ($model->isDirty(static::getDisplayOrderField())) {
                $oldOrder = $model->getOriginal(static::getDisplayOrderField());
                $newOrder = $model->{static::getDisplayOrderField()};
                $model->setDisplayOrder($newOrder, $oldOrder);
            }
        });
        static::deleting(function ($model) {
            $oldOrder = $model->getOriginal(static::getDisplayOrderField());
            $newOrder = $model->getDisplayOrderFilter()->count();
            $model->setDisplayOrder($newOrder, $oldOrder);
        });
        static::deleted(function ($model) {
            $model->preventDisplayOrderGasps();
        });
    }

    protected static function getDisplayOrderField()
    {
        if (property_exists(self::class, 'displayOrderField')) {
            return self::$displayOrderField;
        }
        return 'd_order';
    }


    protected function getDisplayOrderFilter()
    {
        return self::query();
    }

    protected function getMaxDisplayOrderField()
    {
        return $this->getDisplayOrderFilter()->max($this->getDisplayOrderField());
    }

    protected function getDisplayOrder()
    {
        if ($this->{static::getDisplayOrderField()} === null) {
            return $this->getMaxDisplayOrderField() + 1;
        }
        return $this->{static::getDisplayOrderField()};
    }

    protected function preventDisplayOrderGasps()
    {
        //prevent gasps!
        $builder = $this->getDisplayOrderFilter();
        DB::statement(DB::raw('set @row:=0'));
        $builder->orderBy(static::getDisplayOrderField())->update([static::getDisplayOrderField() => DB::raw('(@row := @row + 1)')]);
    }


    protected function setDisplayOrder($newOrder, $currentOrder = null)
    {

        if ($currentOrder === null) {
            $currentOrder = $this->getDisplayOrder();
        }


        if ($newOrder != $currentOrder) {
            $builder = $this->getDisplayOrderFilter()->where($this->primaryKey, '!=', $this->getKey());
            if ($newOrder > $currentOrder) {
                $builder
                    ->where(function ($q) use ($newOrder, $currentOrder) {
                        $q->where(static::getDisplayOrderField(), '<=', $newOrder)
                            ->where(static::getDisplayOrderField(), '>',  $currentOrder);
                    })
                    ->update([
                        static::getDisplayOrderField() => DB::raw(static::getDisplayOrderField() . '-1')
                    ]);
            } else {
                $builder
                    ->where(function ($q) use ($newOrder, $currentOrder) {
                        $q->where(static::getDisplayOrderField(), '>=', $newOrder);
                    })->update([
                        static::getDisplayOrderField() => DB::raw(static::getDisplayOrderField() . '+1')
                    ]);
            }



            $this->{static::getDisplayOrderField()} = $newOrder;
        }
    }
}
