<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Description of GenericResource
 *
 * @author pablo
 */
class GenericResource extends Resource
{

    protected $requestUser;

    public function __construct($resource, $requestUser)
    {
        parent::__construct($resource);
        $this->requestUser = $requestUser;
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
