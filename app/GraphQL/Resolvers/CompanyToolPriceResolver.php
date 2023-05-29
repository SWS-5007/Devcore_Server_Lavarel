<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\CompanyToolPrice;
use App\Services\CompanyToolPriceService;

class CompanyToolPriceResolver extends GenericResolver
{

    public function __construct()
    {
        parent::__construct('core/companyTool', CompanyToolPrice::class, 'id', false);
    }

    protected function createServiceInstance()
    {
        return CompanyToolPriceService::instance();
    }


}
