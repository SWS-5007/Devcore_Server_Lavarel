<?php

namespace App\GraphQL\Resolvers;

use App\Lib\GraphQL\GenericResolver;
use App\Lib\Models\Notification;
use App\Models\Company;
use App\Services\CompanyService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Cookie;
use \Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

class CompanyResolver extends GenericResolver
{

    public function __construct()
    {
        parent::__construct('core/company', Company::class, 'id', true);
    }

    // public function getUser()
    // {
    //     return auth()->user();
    // }


    protected function createServiceInstance(): CompanyService
    {

        return CompanyService::instance();
    }


    function createCompany($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $result = CompanyService::instance()->createCompany($args["input"]);
        // if ($result) {
        //     Subscription::broadcast('newNotification', new Notification('user_updated', $result));
        // }
        return $result;
    }

    function updateCompany($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $result = CompanyService::instance()->updateCompany($args["input"]);
//         if ($result) {
//             Subscription::broadcast('newNotification', new Notification('user_updated', $result));
//         }
        return $result;
    }

    function deleteCompany($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        // dd($args);
        $result = CompanyService::instance()->deleteCompany($args);
        // if ($result) {
        //     Subscription::broadcast('newNotification', new Notification('user_updated', $result));
        // }
        return $result;
    }

}
