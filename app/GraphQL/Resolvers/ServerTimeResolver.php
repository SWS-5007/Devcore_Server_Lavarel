<?php
namespace App\GraphQL\Resolvers;

use App\Lib\Filter\Filter;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\User;

class ServerTimeResolver{
    function serverTime($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        return now();
    }

}