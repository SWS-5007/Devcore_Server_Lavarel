<?php

namespace App\GraphQL\Resolvers;

use App\Services\AccountService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Cookie;
use \Nuwave\Lighthouse\Exceptions\AuthenticationException;
use App\Services\ProjectService;
use App\Services\UserService;
use App\Lib\Models\Permissions\Role;
use App\Models\User;
use App\Lib\Utils;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelHasRole;

class AuthResolver
{

    public function guard()
    {
        return auth('api');
    }


    function login($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $credentials = (collect($args))->get('input');
        if (!$token = $this->guard()->attempt(['email' => $credentials['username'], 'password' => $credentials['password']],  $credentials['remember'])) {

            throw new AuthenticationException(__("auth.failed"));
        }

       // Cookie::queue('_token', $token, config('session.lifetime'), config('session.path'),  config('session.host'), config('session.secure'), config('session.http_only'));

        return [
            'access_token' => $token->access_token,
            'token_type' => 'bearer',
            'expires_at' => $token->expires_at,
            'refresh_token' => $token->refresh_token,
            'user' => $token->user,
        ];
    }

    function session($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $token = auth()->token();
        if (!$token) {
            return $this->respondWithToken($token);
        }
        return [];
    }

    function logout($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        auth('api')->logout(true);
        // Cookie::queue(
        //     Cookie::forget('_token')
        // );
        return true;
    }

    function requestResetPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $username = (collect($args))->get('username');
        return AccountService::instance()->requestResetPassword('email', $username);
    }

    function resetPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $req = (collect($args));
        $result = AccountService::instance()->resetPassword($req->get('code'), $req->get('username'));
        auth()->login($result['user']);
        $token = auth()->token();
        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return array
     */
    protected function respondWithToken(object $token): array
    {
        $accessToken = $token->access_token;
        $expiresAt = $token->expires_at;
        $refreshToken = $token->refresh_token;
        $user = $token->user;
        if ($token) {
            return [
                'access_token' => $accessToken,
                'token_type' => 'bearer',
                'expires_at' => $expiresAt,
                'refresh_token' => $refreshToken,
                'user' => $user,
            ];
        }
    }

    public function userRegister($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = UserService::instance()->find()->where('email', $args['input']['email'])->first();
        $role = Role::where('name', 'User')->first();
        if (!$user) {
            $user = User::create([
                'first_name'            => $args['input']['first_name'],
                'last_name'             => $args['input']['last_name'],
                'email'                 => $args['input']['email'],
                'password'              => Utils::encodePassword($args['input']['password']),
                'phone'                 => $args['input']['phone'],
            ]);
        }

        // Update
        $user->company_id = $args['input']['company_id'];
        $user->company_role_id   = $args['input']['company_role_id'];
        $user->is_super_admin = 0;
        $user->save();

        ModelHasRole::insert(
            array(
                "role_id"       => $role->id,
                "model_type"    => "user",
                "model_id"      => $user->id
            )
        );

        $projectInst = ProjectService::instance()->addUser($args['input']['project_id'], $user->id);
        return $user;
    }
}
