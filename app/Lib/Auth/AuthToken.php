<?php

namespace App\Lib\Auth;

use App\Http\Middleware\Authenticate;
use App\Lib\Models\HasPropertiesColumnTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AuthToken extends Model
{
    use HasPropertiesColumnTrait;

    //protected $primaryKey = 'access_token'; // or null
    protected $table = 'auth_tokens';
    public $incrementing = false;
    protected static $propertiesColumnName = 'payload';

    protected $guarded = [];
    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at',
        'refresh_token_expires_at',
        'revoked_at'
    ];

    protected $casts = [
        'properties' => 'array'
    ];

    public function revoke()
    {
        $this->revoked_at = Carbon::now('UTC');
        $this->save();
    }

    public function user()
    {
        //return $this->belongsTo(config('token-auth.user'), 'user_id', app(config('token-auth.user'))->getKeyName());
        return $this->morphTo('user', 'model_type', 'model_id', 'id');
    }

    public function expired()
    {
        if (!$this->expires_at) {
            return false;
        }
        return $this->revoked_at ? true : Carbon::now('UTC')->isAfter($this->expires_at->addSeconds(config('token-auth.grace_period', 0)));
    }

    public function refreshExpired()
    {
        return $this->revoked_at ? true : ($this->refresh_token_expires_at ? Carbon::now('UTC')->isAfter($this->refresh_token_expires_at) : false);
    }

    public function getAccessTokenAttribute()
    {
        return Crypt::encrypt($this->attributes['access_token']);
    }

    public function getRefreshTokenAttribute()
    {
        return Crypt::encrypt($this->attributes['refresh_token']);
    }

    public function generateRefreshToken($revoke = true)
    {
        $token = AuthToken::createForUser($this->user);
        if ($revoke && $token) {
            //$this->expires_at = Carbon::now('UTC');
            $this->revoked_at = Carbon::now('UTC');
            $this->save();
        }
        return $token;
    }

    public function extendTTL($seconds = 0)
    {
        if ($seconds > 0) {
            $this->expires_at = Carbon::now('UTC')->addSeconds($seconds);
            $this->refresh_token_expires_at = config('token-auth.refresh_token_ttl', 3600) > 0 ? Carbon::now('UTC')->addSeconds(config('token-auth.refresh_token_ttl', 3600) * 60) : null;
            $this->save();
        }
        return $this;
    }


    public static function generateTokenStr()
    {

        return Str::random(100);
    }

    public static function createForUser(Authenticatable $user, $extendedTtl = false, $client = "default", $payload = null)
    {

        $ttl = config('token-auth.ttl', 3600) > 0 ? Carbon::now('UTC')->addSeconds(config('token-auth.ttl', 3600)) : null;
        if ($extendedTtl === true) {
            $ttl = config('token-auth.extended-ttl', 2592000) > 0 ? Carbon::now('UTC')->addSeconds(config('token-auth.extended-ttl', 2592000)) : null;
        } elseif (is_numeric($extendedTtl)) {
            $ttl = Carbon::now('UTC')->addSeconds($extendedTtl);
        }

        $token = AuthToken::create([
            'access_token' => AuthToken::generateTokenStr(),
            'refresh_token' => AuthToken::generateTokenStr(),
            'extended_ttl' => $extendedTtl,
            'model_type' => 'user',
            'client' => $client,
            'model_id' => $user->getAuthIdentifier(),
            'expires_at' => $ttl,
            'payload' => $payload,
            'refresh_token_expires_at' => config('token-auth.refresh_token_ttl', 3600) > 0 ? Carbon::now('UTC')->addSeconds(config('token-auth.refresh_token_ttl', 3600) * 60) : null,
        ]);
        $token->user = $user;
        return $token;
    }
}
