<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\Issue;
use App\Models\IssueEffect;
use App\Models\IssueEffectTemplate;
use App\Services\IssueEffectService;
use App\Services\IssueEffectTemplateService;
use App\Services\IssueService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class IssueEffectTemplateResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('improve/idea', IssueEffectTemplate::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return IssueEffectTemplateService::instance();
    }

    public function deleteMany($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->deleteMany($args);
    }

}
