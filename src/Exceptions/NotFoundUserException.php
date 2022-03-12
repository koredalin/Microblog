<?php

namespace App\Exceptions;

use Exception;
use App\Controllers\Response\ResponseStatuses;

/**
 * Description of NotFoundUserException
 *
 * @author Hristo
 */
class NotFoundUserException extends Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $code = ResponseStatuses::NOT_FOUND;
        $exceptionClass = basename(str_replace('\\', '/', get_class())) . '. ';
        parent::__construct($exceptionClass . $message, $code, $previous);
    }
}
