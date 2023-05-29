<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\Tool;
use App\Services\ToolService;

class ToolResolver extends GenericResolver
{

    public function __construct()
    {
        parent::__construct('core/tool', Tool::class, 'id', false);
    }

    protected function createServiceInstance()
    {
        return ToolService::instance();
    }
}
