<?php

namespace App\GraphQL\Resolvers;

use App\Lib\Models\Notification;
use App\Models\User;
use App\Services\AccountService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Cookie;
use \Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

class AccountResolver
{
    public function getUser()
    {
        $user = auth()->user();

        return $user;
    }

    function updateProfile($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $result = AccountService::instance()->updateProfile($args["input"]);
        return $result;
    }

    public function updateUserLanguage($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return AccountService::instance()->userLanguageUpdate($args['input']);
    }

    public function updateUserDisplayScore($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return AccountService::instance()->userDisplayScoreUpdate($args['input']);
    }

    public function updateUserIdeaIntro($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return AccountService::instance()->userIdeaIntroUpdate($args['input']);
    }

    public function updateUserProfileRewardActive($root, array $args, GraphQLContext $context, ResolveInfo $info){
        return AccountService::instance()->userProfileRewardUpdate($args['input']);
    }

    function upateMyCompany($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $result = AccountService::instance()->updateCompany($args["input"]);
        // if ($result) {
        //     Subscription::broadcast('newNotification', new Notification('user_updated', $result));
        // }
        return $result;
    }
}
