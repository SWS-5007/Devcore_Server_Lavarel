<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasUserTrait
{
    protected static function bootHasUserTrait()
    {
        static::saving(function ($model) {
            if (!$model->{$model->getUserColumnName()}) {
                $model->{$model->getUserColumnName()} = config("app.user_id", null);
            }
        });
    }

    public function getUserColumnName()
    {
        if (property_exists($this, 'userColumnName')) {
            return $this->userColumnName;
        }
        return 'user_id';
    }

    public function scopeCurrentUser(Builder $builder, $userId = null)
    {
        return $builder->where($this->getUserColumnName(), ($userId ? $userId : config("app.user_id")));
    }

    public function user()
    {
        return $this->belongsTo(User::class, $this->getUserColumnName(), 'id');
    }
}
