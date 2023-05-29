<?php

namespace App\Models;

use App\Lib\Models\HasPropertiesColumnTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Token extends Model
{

    use HasPropertiesColumnTrait;

    public $guarded = [];

    const PURPOSES = [
        'reset_password',
        'activate_account',
        'otp'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'properties' => 'array'
    ];

    /// TODO: It's there any way to make this works for an eager loader????
    public function owner()
    {
        return $this->morphTo('owner', 'model_type', 'field_value', $this->field_name);
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = Hash::make($value);
        return $this;
    }
}
