<?php

namespace App\Http\Middleware;

use App\Lib\Http\Middleware\Cors;
use Closure;
use Illuminate\Support\Facades\Log;

class CorsMiddleware /*extends Cors*/
{



    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->header('Origin')) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $request->header('Origin'))
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header("Access-Control-Allow-Headers", "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-User-Id, X-Client-Version, X-Client, X-CSRF-Token")
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        }

        return $next($request);
    }
}
