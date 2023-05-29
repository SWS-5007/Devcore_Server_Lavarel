<?php

namespace App\Lib\GraphQL\Directives;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Support\Utils;

class MetadataDirective extends BaseDirective implements FieldResolver
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name()
    {
        return 'metadata';
    }

    function getUser()
    {
        return auth($this->directiveArgValue("guard", null))->user();
    }

    function getResolver($argumentName = "resolver")
    {
        $argumentParts = explode(
            '@',
            $this->directiveArgValue($argumentName, config('lighthouse.custom.metadataResolver'))
        );

        if (
            count($argumentParts) > 2
            || empty($argumentParts[0])
        ) {
            throw new DefinitionException(
                "Directive '{$this->name()}' must have an argument '{$argumentName}' in the form 'ClassName@methodName' or 'ClassName'"
            );
        }

        if (empty($argumentParts[1])) {
            $argumentParts[1] = '__invoke';
        }

        return $argumentParts;
    }

    /**
     * Resolve the field directive.
     *
     * @param FieldValue $value
     * @param \Closure   $next
     *
     * @return FieldValue
     */
    public function resolveField(FieldValue $fieldValue): FieldValue
    {

        [$className, $methodName] = $this->getResolver();

        $namespacedClassName = $this->namespaceClassName(
            $className,
            $fieldValue->defaultNamespacesForParent()
        );

        $resolver = Utils::constructResolver($namespacedClassName, $methodName);

        $additionalData = $this->directiveArgValue('args');

        $params = [
            'module' => $this->directiveArgValue('module', 'core/')
        ];

        return $fieldValue->setResolver(
            function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)  use ($resolver, $additionalData, $params) {
                return $resolver(
                    $root,
                    array_merge($args, ['directive' => $additionalData], $params),
                    $context,
                    $resolveInfo
                );
            }
        );
    }
}
