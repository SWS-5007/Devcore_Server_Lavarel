<?php

namespace App\Lib\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest implements IRequest
{
    public function rules()
    {
        return [];
    }
}
