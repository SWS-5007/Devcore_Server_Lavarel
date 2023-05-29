<?php

namespace App\Http\Middleware;

use App\Lib\Utils;
use Closure;
use Illuminate\Support\Facades\Log;

class Lang
{
    const KEY = "locale";

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $locale = $request->get(self::KEY);

        if (!$locale) {
            $locale = $request->header(self::KEY);
        }

        if (!$locale) {
            $locale = $request->header(self::KEY);
        }

        try {
            if (!$locale) {
                $locale = $request->session(self::KEY);
            }
        } catch (\Exception $ex) {
        }

        if (!$locale) {
            $locale = $request->getPreferredLanguage(Utils::getAvailableLangs());
        }

        if (!in_array($locale, Utils::getAvailableLangs())) {
            $locale = config("app.lang");
        }

        app()->setLocale($locale);
        return $next($request);
    }
}
