<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Loaders\UserStatsLoader;
use App\Lib\GraphQL\Exceptions\NotFoundException;
use App\Lib\GraphQL\GenericResolver;
use App\Lib\Models\Permissions\Role;
use App\Models\User;
use App\Models\Project;
use App\Services\UserDeviceService;
use App\Services\ProjectService;
use App\Services\UserService;
use App\Notifications\UserInvitedNotification;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserResolver extends GenericResolver
{
    public function __construct()
    {
        parent::__construct('auth/user', User::class, 'id', true);
    }

    protected function createServiceInstance(): UserService
    {
        return UserService::instance();
    }



    public function resetPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //$args = collect($args);
        $entity = $this->getServiceInstance()->findByPrimaryKey($this->extractIdFromArgs($args));
        if (!$entity) {
            throw new NotFoundException();
        }
        if ($this->canOrFail('reset_password', $entity)) {
            $result = $this->getServiceInstance()->resetPassword($entity);
            if ($result) {
                $this->setResponseMessage(__("We have sent a notification to the user with the new password!"));
            }
            return $result;
        }
    }

    public function getActiveMilestone(User $user,array $args, GraphQLContext $context, ResolveInfo $info){
        return $user->activeMilestone();
    }

    public function getUserRank(User $user,array $args, GraphQLContext $context, ResolveInfo $info){
        return $user->getUserRank();
    }

    public function getUserSharedValue(User $user,array $args, GraphQLContext $context, ResolveInfo $info){
        return $user->getUserSharedValue();
    }

    public function getUsersProjectIdeaUsage(User $user,array $args, GraphQLContext $context, ResolveInfo $info){
        return $user->getUsersProjectIdeaUsage();
    }

    public function getRoleScores(User $user,array $args, GraphQLContext $context, ResolveInfo $info){
        return $user->getRoleScores();
    }

//    public function getUserEngageScore(User $user,array $args, GraphQLContext $context, ResolveInfo $info){
//        return $user->engageScore();
//    }

    public function setDeviceToken($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $results = UserDeviceService::instance()->find()
                        ->where('token', $args['input']['token'])
                        ->first();

        /*** Device Token Not Found */
        if(!$results){

                $service = UserDeviceService::instance()->create([
                    'token'=>$args['input']['token'],
                    'type' =>  $args['input']['type'],
                    'user_id' => $args['input']['id'],
                ]);

                return $service;
        }else{ /// User Update Against Device Token
            $results->user_id = $args['input']['id'];
            $results->save();
            return $results;
        }

       return false;
    }

    public function userInvite($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $result = $this->getServiceInstance()->find()->where('email', $args['input']['email'])->first();
        $role = Role::where('name', 'User')->first();
        if (!$result) {
            // generate random password
            $permitted_chars = '123456789abcdefghijklmnopqrstuvwxyz';
            $random_pwd = substr( str_shuffle($permitted_chars), 0, 8);

            $result = $this->getServiceInstance()->create([
                'first_name'            => $args['input']['first_name'],
                'last_name'             => $args['input']['last_name'],
                'email'                 => $args['input']['email'],                
                'yearly_costs'          => 0,
                'must_change_password'  => 0,
                'is_super_admin'        => 0,
                'password'              => $random_pwd,
                'lang'                  => 'en',
                'status'                => 'PENDING',
                'total_gains'           => 0,
                'total_losses'          => 0,
                'consolidated_value'    => 0,
                'total_evaluations'     => 0,
                'notifications'         => 0,
                'profile_score_display' => 0,
                'user_engage_score'     => 0,
                'profile_idea_intro'    => 0,
                'company_role_id'       => $args['input']['company_role_id'],
                'role_id'               => $role->id
            ]);
        }
        $projectInst = ProjectService::instance()->addUser($args['input']['project_id'], $result->id);
        return $result;
    }

}
