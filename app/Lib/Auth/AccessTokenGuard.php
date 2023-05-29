<?php

namespace App\Lib\Auth;

use App\Lib\Context;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AccessTokenGuard implements Guard
{
    use GuardHelpers;
    private $inputKey = '';
    private $storageKey = '';
    private $refreshInputKey = '';
    private $refreshStorageKey = '';
    private $guardName = '';
    private $request;
    private $token = null;

    public function __construct(UserProvider $provider, Request $request, $configuration)
    {
        $this->provider = $provider;
        $this->request = $request;
        // key to check in request
        $this->inputKey = isset($configuration['input_key']) ? $configuration['input_key'] : config('token-auth.input_key');
        // key to check in database
        $this->storageKey = isset($configuration['storage_key']) ? $configuration['storage_key'] : config('token-auth.storage_key');

        // key to check in request
        $this->refreshInputKey = isset($configuration['input_refresh']) ? $configuration['input_refresh'] : config('token-auth.input_refresh');
        // key to check in database
        $this->refreshStorageKey = isset($configuration['storage_refresh']) ? $configuration['storage_refresh'] : config('token-auth.storage_refresh');

        $this->guardName = isset($configuration['guard_name']) ? $configuration['guard_name'] : config('token-auth.guard_name');
    }

    public function check()
    {
        return !$this->guest();
    }

    public function guest()
    {
        return !$this->user();
    }


    public function token()
    {
        if (!is_null($this->token)) {
            return $this->token;
        }
        $user = null;

        // retrieve via token
        $token = $this->getTokenFromRequest();
        if (!empty($token)) {

            //decrypt the token
            $token = Crypt::decrypt($token);
            // the token was found, how you want to pass?
            $token  = AuthToken::with('user')->where($this->storageKey, $token)->first();
            $this->token = $token;
            if ($this->token) {
                $expired = $this->token->expired();
                if(!$expired) {
                    $user = ($this->provider->retrieveByToken($this->storageKey, $token));
                    //extend the token
                    $this->extendToken();
                }

            } else {
                $this->token = null;
            }
        } else {
            $this->token = null;
        }
        $this->setUser($user);
        $this->addCookie();

        return $this->token;
    }

    public function user()
    {
        $this->token();

        if($this->token){
            $expired = $this->token->expired();
            if(!$expired){
                if(!is_null($this->user)){
                    return $this->user;
                }
            }
        }
//        if(!is_null($this->user))
//
//        if (!is_null($this->user) && $this->token && (!$this->token->expired())) {
//            return $this->user;
//        }
    }

    public function logout()
    {
        if (!$this->token()) {
            throw new AuthenticationException();
        }
        $this->token->revoke();
        $this->token = null;
        $this->user = null;
        $this->addCookie();
        return true;
    }

    public function extendToken($seconds = 0)
    {
        if (!$this->token() || $this->token->expired()) {
            throw new AuthenticationException();
        }

        if (!$seconds) {
            if ($this->token->extended_ttl) {
                $seconds = config('token-auth.extended-ttl', 2592000);
            } else {
                $seconds = config('token-auth.ttl', 3600);
            }
        }

        $this->token->extendTTL($seconds);
        $this->addCookie();
        return $this->token;
    }


    public function refreshToken()
    {

        $token = $this->getRefreshTokenFromRequest();
        if (!$token) {
            throw new AuthenticationException();
        }

        $token = AuthToken::where($this->refreshStorageKey, Crypt::decrypt($token))->first();

        if (!$token || ($token->refreshExpired())) {
            throw new AuthenticationException();
        }
        $this->token = $token->generateRefreshToken();
        $this->addCookie();
        return $this->token;
    }

    public function getRefreshTokenFromRequest()
    {
        $token = $this->request->input($this->refreshInputKey);
        if (empty($token)) {
            $token = $this->request->cookie($this->refreshInputKey);
        }

        return $token;
    }

    /**
     * Get the token for the current request.
     * @return string
     */
    public function getTokenFromRequest()
    {

        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->cookie($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }



        return $token;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        $credentials = [$this->storageKey => $credentials[$this->inputKey]];

        if ($user = $this->provider->retrieveByCredentials($credentials)) {
            return $user;
        }

        return false;
    }

    public function id()
    {
        if ($this->check()) {
            return $this->user()->getAuthIdentifier();
        }
        return null;
    }

    public function setUser($user)
    {
        if ($this->guardName) {
            auth()->shouldUse($this->guardName);
        }

        $this->user = $user;
        request()->setUserResolver(function () use ($user) {
            return $user;
        });

        Context::get()->setUser($user);

        return $user;
    }

    public function constructPayload()
    {
        return [
            'ip' => Context::get()->getRequest()->getClientIp(),
            'user-agent' => Context::get()->getRequest()->header('User-Agent'),
        ];
    }

    public function getClientType()
    {
        $client = Context::get()->getRequest()->header('X-Client', 'web');
        $version = Context::get()->getRequest()->header('X-Client-Version', '1.0');
        return "$client:$version";
    }

    public function login($user, $extendedTtl = false)
    {
       # xdebug_break();
        if ($this->token) {
            $this->logout();
        }

        $this->token = AuthToken::createForUser($user, $extendedTtl, $this->getClientType(), $this->constructPayload());
        $this->setUser($user);
        $this->addCookie();
        return $this->token;
    }

    public function loginUsingId($identifier, $extendedTtl = false)
    {
        return $this->login($this->provider->retrieveById($identifier), $extendedTtl);
    }

    public function once(array $credentials = [])
    {
        if (($user = $this->provider->retrieveByCredentials($credentials))) {

            if ($this->provider->validateCredentials($user, $credentials)) {
                $this->setUser($user);
                return $user;
            }
        }
    }

    public function attempt(array $credentials = [], $remember = false)
    {

        if (($user = $this->provider->retrieveByCredentials($credentials))) {

            if ($this->provider->validateCredentials($user, $credentials)) {
                return $this->login($user, $remember);
            }
        }
    }

    public function addCookie()
    {
        $thisToken = null;
        $useCookies = config('token-auth.use_cookies', true);
        $thisToken = $this->token;
        if ($thisToken && $useCookies) {
            $expirationToken = $this->token->expires_at ? $this->token->expires_at : Carbon::now('UTC')->addDays(180);
            $expiration = ceil(Carbon::now('UTC')->diffInMinutes($expirationToken, true));

            Cookie::queue(
                $this->inputKey,
                $this->token->{$this->storageKey},
                $expiration,
                config('token-auth.cookie_path'),
                config('token-auth.cookie_domain'),
                config('token-auth.cookie_secure'),
                config('token-auth.cookie_http_only', false)
            );
        } else {
            //\Cookie::queue(\Cookie::forget('myCookie'));
            if (Cookie::get($this->inputKey)) {
                Cookie::queue(
                    Cookie::forget($this->inputKey)
                );
            }
        }
    }

    public function viaRemember()
    {
        return false;
    }

}
