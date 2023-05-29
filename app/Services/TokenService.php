<?php

namespace App\Services;

use App\Lib\Services\GenericService;
use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class TokenService extends GenericService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(Token::class, false);
    }

    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $instance->fill($data);
        return $instance;
    }

    public function checkValue(Token $token, $rawValue)
    {
        return Hash::check($rawValue, $token->value);
    }

    public function checkAndUseToken($value, $purpose, $ownerKeyValue, $ownerType = 'user', $ownerKeyName = null, $deleteUsedToken = true)
    {
        if ($ownerType === 'user') {
            if (!$ownerKeyName) {
                if (filter_var($ownerKeyName, FILTER_VALIDATE_EMAIL)) {
                    $ownerKeyName = 'email';
                } else {
                    $ownerKeyName = 'id';
                }
            }
        } else {
            if (!$ownerKeyName) {
                $ownerKeyName = 'id';
            }
        }

        $tokens = $this->getTokenByParams([
            'model_type' => $ownerType,
            'field_name' => $ownerKeyName,
            'field_value' => $ownerKeyValue,
            'purpose' => $purpose
        ])->values();


        $found = null;
        if (count($tokens)) {
            $i = 0;
            do {
                $token = $tokens[$i];
                if ($this->checkValue($token, $value)) {
                    $found = $token;
                    if($deleteUsedToken){
                        $token->delete();
                    }
                   
                }
                $i++;
            } while (!$found && $i < count($tokens));
        }

        return $found;
    }

    public function clearExpiredTokens()
    {
        Token::where('expires_at', '<', Carbon::now())->delete();
    }

    /**
     * @return Collection
     */
    public function getTokenByParams($params, $allowExpired = false)
    {
        $tokens = Token::where($params);
        if (!$allowExpired) {
            $tokens->where('expires_at', '>=', Carbon::now());
        }
        return $tokens->get();
    }
}
