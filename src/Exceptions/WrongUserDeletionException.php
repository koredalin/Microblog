<?php

namespace App\Exceptions;

use Exception;
use App\Controllers\Response\ResponseStatuses;

/**
 * Description of NotFoundUserException
 *
 * @author Hristo
 */
class WrongUserDeletionException extends Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $code = ResponseStatuses::FORBIDDEN;
        parent::__construct($message, $code, $previous);
    }
}
