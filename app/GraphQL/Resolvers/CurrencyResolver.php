<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\Currency;
use App\Services\CurrencyService;

class CurrencyResolver extends GenericResolver
{

    public function __construct()
    {
        parent::__construct('core/currency', Currency::class, 'code', false);
    }

    protected function createServiceInstance()
    {
        return CurrencyService::instance();
    }
}
