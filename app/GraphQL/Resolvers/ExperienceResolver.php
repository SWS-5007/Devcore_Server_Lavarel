<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Models\ExperienceUser;
use App\Services\ExperienceService;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Type\Definition\ResolveInfo;

class ExperienceResolver extends GenericResolver
{

    public function __construct()
    {
        parent::__construct('auth/user', ExperienceUser::class, 'id', true);
    }

    protected function createServiceInstance()
    {
        return ExperienceService::instance();
    }

    public function increaseExperience($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return $this->createServiceInstance()->increaseExperience($args['input']);
    }
}
