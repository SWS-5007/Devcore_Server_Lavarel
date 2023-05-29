<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        return file_get_contents(public_path('frontend/index.html'));
    }
}
