<?php

namespace App\Lib\GraphQL\Exceptions;

use App\Lib\Utils\HttpStatusCode;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Illuminate\Validation\UnauthorizedException as IUnauthorizedException;

class UnauthorizedException extends IUnauthorizedException implements RendersErrorsExtensions
{

    const STATUS = 403;

    /**
     * CustomException constructor.
     * 
     * @param  string  $message
     * @param  string  $reason
     * @return void
     */
    public function __construct(string $message = 'This action is unauthorized')
    {
        parent::__construct(__($message));
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

    public function getStatusCode()
    {
        return 403;
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
        return str_replace(' ', '_', strtolower(HttpStatusCode::getStatus($this->getStatusCode())));
    }

    public function getStatusMessage(): string
    {
        return HttpStatusCode::getStatus($this->getStatusCode());
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
            'category' => $this->getCategory(),
            'message' => $this->getMessage(),
            'status' => $this->getStatusCode(),
            'statusMessage' => $this->getStatusMessage(),
        ];
    }
}
