<?php

namespace App\Lib\GraphQL\Exceptions;

use Closure;
use GraphQL\Error\Error;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Nuwave\Lighthouse\Execution\ErrorHandler;
use GraphQL\Error\ClientAware;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ValidationException extends BadRequestHttpException implements RendersErrorsExtensions
{
    /**
     * @var string
     */
    const CATEGORY = 'validation';

    protected $message = 'The given data was invalid';

    /**
     * CustomException constructor.
     *
     * @param  string  $message
     * @param  string  $reason
     * @return void
     */
    public function __construct(string $message =  'The given data was invalid', $previous)
    {
        parent::__construct(__($message), $previous);
    }
    /**
     * Returns true when exception message is safe to be displayed to a client.
     *
     * @api
     * @return bool
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * Returns string describing a category of the error.
     *
     * Value "graphql" is reserved for errors produced by query parsing or validation, do not use it.
     *
     * @api
     * @return string
     */
    public function getCategory(): string
    {
        return self::CATEGORY;
    }


    /**
     * Return the content that is put in the "extensions" part
     * of the returned error.
     *
     * @return array
     */
    public function extensionsContent(): array
    {
        $previous = $this->getPrevious();

        $validation = [];

        if ($previous instanceof ValidationValidationException) {
            $errors = $previous->errors();
        }

        return [
            "validation" => $errors
        ];
    }
}
