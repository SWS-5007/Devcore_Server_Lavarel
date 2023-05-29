<?php

namespace App\Lib\GraphQL\Exceptions;

use Closure;
use GraphQL\Error\Error;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Nuwave\Lighthouse\Execution\ErrorHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GraphQL\Error\ClientAware;

class NotFoundException extends NotFoundHttpException implements RendersErrorsExtensions
{
    /**
     * @var string
     */
    const CATEGORY = 'not_found';

    protected $message = 'Resource not found';

    /**
     * CustomException constructor.
     * 
     * @param  string  $message
     * @param  string  $reason
     * @return void
     */
    public function __construct(string $message = 'Resource not found', string $reason = 'Resource not found')
    {
        parent::__construct(__($message));

        $this->reason = __($reason);
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
        return [
            // 'some' => 'additional information',
            // 'reason' => $this->reason,
        ];
    }
}
