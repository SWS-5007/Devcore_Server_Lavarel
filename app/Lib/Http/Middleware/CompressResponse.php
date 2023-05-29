<?php

namespace App\Lib\Http\Middleware;

use App\Lib\Utils;
use Closure;

class CompressResponse
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
        if (!$request->wantsJson()) {
            $response = $next($request);
            $buffer = $response->getContent();
            //$buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);
            $buffer = Utils::compressHtml($buffer);
            $response->setContent($buffer);
            //ini_set('zlib.output_compression', 'On'); // If you like to enable GZip, too!
            return $response;
        }
        return $next($request);
    }
}
