<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MobileController extends Controller
{
    public function index()
    {
        return file_get_contents(public_path('mobile/index.html'));
    }
}
