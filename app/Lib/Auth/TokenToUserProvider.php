<?php

namespace App\Lib\Auth;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;

class TokenToUserProvider implements UserProvider
{
    private $token;
    private $user;

    public function __construct(User $user, AuthToken $token)
    {

        $this->user = $user;
        $this->token = $token;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function token()
    {
        return $this->token;
    }

    

    public function retrieveById($identifier)
    {

        return $this->user->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {

        /*$token = $this->token->with('user')->where($identifier, $token)->first();
        return $token && $token->user && (!$token->expired()) ? $token->user : null;*/
        if($token){
            return $token->user;
        }
        
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // update via remember token not necessary
    }

    public function retrieveByCredentials(array $credentials)
    {
        // implementation upto user.
        // how he wants to implement -
        // let's try to assume that the credentials ['username', 'password'] given

      
        $user = $this->user->query();
       
        foreach ($credentials as $credentialKey => $credentialValue) {
            if (!Str::contains($credentialKey, 'password')) {
                $user->where($credentialKey, $credentialValue);
            }
        }


        return $user->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = isset($credentials['password']) ? $credentials['password'] : null;
        return app('hash')->check($plain, $user->getAuthPassword());
    }
}
