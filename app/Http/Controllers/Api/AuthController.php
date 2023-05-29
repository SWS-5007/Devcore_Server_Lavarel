<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @return Auth
     */
    public function guard()
    {
        return auth('api');
    }

    public function login(Request $request)
    {
        $this->guard()->attempt($request->all());
        return $this->guard()->token();
    }

    public function refresh(Request $request)
    {
        //auth('api')->attempt($request->all());
        return $this->guard()->refreshToken();
    }

    public function extend(Request $request)
    {
        //auth('api')->attempt($request->all());
        return $this->guard()->extendToken();
    }


}
