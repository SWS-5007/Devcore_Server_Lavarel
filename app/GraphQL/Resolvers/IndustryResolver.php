<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\Industry;
use App\Services\IndustryService;

class IndustryResolver extends GenericResolver
{

    public function __construct()
    {
        parent::__construct('core/industry', Industry::class, 'id', false);
    }

    protected function createServiceInstance()
    {
        return IndustryService::instance();
    }

    
}
