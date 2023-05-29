<?php

namespace App\Lib\GraphQL\Directives;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FieldAccessDirective extends BaseDirective implements FieldMiddleware
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name()
    {
        return 'fieldAccess';
    }

    function getUser()
    {
        return auth($this->directiveArgValue("guard", null))->user();
    }

    /**
     * Resolve the field directive.
     *
     * @param FieldValue $value
     * @param \Closure   $next
     *
     * @return FieldValue
     */
    public function handleField(FieldValue $fieldValue, \Closure $next)
    {
        $resolver = $fieldValue->getResolver();

        return $next($fieldValue->setResolver(function ($root, $args, $context) use ($resolver) {
            $permissions = $this->directiveArgValue("permissions", []);
            $modifier = $this->directiveArgValue("modifier", "any");
            if (!is_array($permissions)) {
                $permissions = [$permissions];
            }
            if ($modifier === "any") {
                if (!Gate::forUser($this->getUser())->any($permissions, $root)) {
                    return null;
                }
            } else {
                if (!Gate::forUser($this->getUser())->allows($permissions, $root)) {
                    return null;
                }
            }


            return call_user_func_array($resolver, func_get_args());
        }));
    }
}
