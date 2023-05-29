<?php

namespace App\Lib\GraphQL;

use App\Lib\Context;
use Illuminate\Support\Facades\Log;

class GraphQLContext extends Context
{
    public $name = 'GRAPHQL';


    public function getUser()
    {
        return $this->user;
    }

    public function runInsideContext($context, $callable)
    {
        $ret = null;
        $currentUser = $this->getUser();


        try {
            $this->setUser($context->user());
            $ret = $callable();
        } finally {
            $this->setUser($currentUser);
        }


        return $ret;
    }
}
