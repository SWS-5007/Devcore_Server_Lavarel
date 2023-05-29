<?php

namespace App\Lib\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GuestUser extends Authenticatable
{

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->name = 'Guest';
        $this->is_authenticated = false;
    }
}
