<?php

namespace App\GraphQL\Resolvers;

use App\Lib\Filter\Filter;
use App\Models\Project;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\User;

class AppConfigResolver
{
    function getConfig($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //return [];
        $retLangs = [];
        $allLangs = config('custom.languages');
        $retLangs = array_filter($allLangs, function ($item) {
            return in_array($item['code'], config('app.available_locales'));
        });
        $retLangs = array_values($retLangs);
        return [
            'langs' => $retLangs,
            'defaultLang' => config('app.default_locale'),
        ];
    }
}
