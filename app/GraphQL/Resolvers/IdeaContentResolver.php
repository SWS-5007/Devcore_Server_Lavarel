<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\IdeaContent;
use App\Services\IdeaContentService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class IdeaContentResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('improve/idea', IdeaContent::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return IdeaContentService::instance();
    }


    public function getPermissionedContents($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        if ($this->canOrFail('readAll')) {
            $list = $this->getServiceInstance()->listPermissionedObjects($this->parseFilter($args));

            $output = $this->constructOutputList($list->get());
            if ($context->user()) {
                $company_role_id = $context->user()->company_role_id;
                $test = $output->filter(function ($content) use ($company_role_id) {
                    $roles = $content->companyRoles()->get()->map(function ($role) {
                        return $role['id'];
                    })->toArray();
                    return in_array($company_role_id, $roles);
                });
            } else {
                return [];
            }

            return $test;
        }
    }

}
