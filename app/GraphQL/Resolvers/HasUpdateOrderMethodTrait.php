<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\Exceptions\NotFoundException;
use App\Lib\GraphQL\GenericResolver;
use App\Models\User;
use App\Services\UserService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait HasUpdateOrderMethodTrait
{
    public function updateDisplayOrder($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        
        $entity = $this->getServiceInstance()->findByPrimaryKey($this->extractIdFromArgs($args['input']));
        if (!$entity) {
            throw new NotFoundException();
        }
        if ($this->canOrFail('update', $entity)) {
           return $this->getServiceInstance()->updateDisplayOrder($entity, $args['input']);
        }
    }
}
