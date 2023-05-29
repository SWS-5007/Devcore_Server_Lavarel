<?php

namespace App\Lib\GraphQL\Exceptions;

use Closure;
use GraphQL\Error\Error;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException as IValidationException;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Nuwave\Lighthouse\Execution\ErrorHandler;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExtensionErrorHandler implements ErrorHandler
{
    /**
     * Handle Exceptions that implement Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions
     * and add extra content from them to the 'extensions' key of the Error that is rendered
     * to the User.
     *
     * @param  \GraphQL\Error\Error  $error
     * @param  \Closure  $next
     * @return array
     */
    public static function handle(Error $error, Closure $next): array
    {
        $underlyingException = $error->getPrevious();

        $newError = null;
        if ($underlyingException instanceof NotFoundHttpException) {
            // Reconstruct the error, passing in the extensions of the underlying exception
            $newError = new NotFoundException($underlyingException->getMessage() ? $underlyingException->getMessage() : 'Resource not found');
        } elseif ($underlyingException instanceof AuthenticationException) {
            // Reconstruct the error, passing in the extensions of the underlying exception
            $newError = new UnauthenticatedException($underlyingException->getMessage() ? $underlyingException->getMessage() : 'asd');
        } elseif ($underlyingException instanceof BadRequestHttpException) {
            // Reconstruct the error, passing in the extensions of the underlying exception
            $newError = new BadRequestException($underlyingException->getMessage() ? $underlyingException->getMessage() : 'Bad request');
        } elseif ($underlyingException instanceof IValidationException) {
            // Reconstruct the error, passing in the extensions of the underlying exception
            $newError = new ValidationException($underlyingException->getMessage() ? $underlyingException->getMessage() : 'Bad request', $underlyingException);
        }elseif($underlyingException instanceof AuthorizationException){
             // Reconstruct the error, passing in the extensions of the underlying exception
             $newError = new UnauthorizedException($underlyingException->getMessage() ? $underlyingException->getMessage() : 'Unauthorized', $underlyingException);
        }

        // if ($underlyingException instanceof RendersErrorsExtensions) {
        //     // Reconstruct the error, passing in the extensions of the underlying exception
        //     $error = new Error(
        //         __($error->message),
        //         $error->nodes,
        //         $error->getSource(),
        //         $error->getPositions(),
        //         $error->getPath(),
        //         $underlyingException,
        //         $underlyingException->extensionsContent()
        //     );
        // } else {
        // }

        if ($newError) {
            // Reconstruct the error, passing in the extensions of the underlying exception
            $error = new Error(
                __($newError->getMessage()),
                $error->nodes,
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                $newError,
                $newError->extensionsContent()
            );
        }



        return $next($error);
    }
}
