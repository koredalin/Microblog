<?php

namespace App\Exceptions;

use Exception;
use App\Controllers\Response\ResponseStatuses;

/**
 * Description of DtoValidationException
 *
 * @author Hristo
 */
class DtoValidationException extends Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $code = ResponseStatuses::UNPROCESSABLE_ENTITY;

        parent::__construct($message, $code, $previous);
    }
}
