<?php

namespace App\Exceptions;

use Exception;
use App\Controllers\Response\ResponseStatuses;

/**
 * Description of AlreadyExistingDbRecordException
 *
 * @author Hristo
 */
class AlreadyExistingDbRecordException extends Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null): Exception
    {
        $code = ResponseStatuses::UNPROCESSABLE_ENTITY;
        
        return parent::__construct($message, $code, $previous);
    }
}
