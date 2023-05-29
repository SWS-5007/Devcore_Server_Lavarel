<?php

namespace App\Lib\GraphQL;

use App\Lib\Context;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BaseResolver
{

    protected function guardName()
    {
        return null;
    }

    /**
     * @return Authorizable
     */
    protected function getUser()
    {
        return GraphQLContext::get()->getUser();
        // if (!Auth::user()) {
        //     Auth::setUser($context->user);
        // }
        //return auth($this->guardName())->user();
    }

    protected function addParamToMetadata($key, $value)
    {
        $current = request()->get('_metadata');
        $current[$key] = $value;
        request()->merge(['_metadata' => $current]);
    }

    protected function setResponseMessage($message, $transPrams = [], $shouldTranslate = true)
    {
        if ($shouldTranslate) {
            $message = __($message, $transPrams);
        }
        $this->addParamToMetadata('message', $message);
    }


    public function resolveInsideContext($context, $function)
    {

        return GraphQLContext::get()->runInsideContext($context, $function);
    }
}
