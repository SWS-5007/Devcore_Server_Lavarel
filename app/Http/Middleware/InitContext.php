<?php

namespace App\Http\Middleware;

use App\Lib\Context;
use App\Lib\GraphQL\GraphQLContext;
use App\Models\Company;
use Closure;
use Illuminate\Support\Facades\Log;

class InitContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $context = 'api', $guard = null)
    {
        $authUserId = auth($guard)->user() ? auth($guard)->user()->id : null;


        if ($context === 'graphql') {
            $context = GraphQLContext::get();
        } else {
            $context = Context::get();
       }

        $context->request = $request;

        if (auth($guard)->user()) {
            $user = auth()->user();
            $company = null;
            $company_id = null;
            if ($user->is_super_admin) {
                $company_id = request()->header('x-company', null);
                if ($company_id) {
                    $company = Company::find($company_id)->first();
                }
            } else {
                $company = $user->company;
                $company_id = $user->company->id;
            }

            config([
                "app.user_id" => $user->id,
                "app.user" => $user,
                "app.company_id" => $company_id,
                "app.company" => $company
            ]);

            $context->user = $user;
            $context->company = $company;
        }

        request()->merge([
            '_metadata' => [
                'message' => ''
            ]
        ]);

        return $next($request);
    }
}
