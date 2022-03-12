<?php

namespace App\Exceptions;

use Exception;
use App\Controllers\Response\ResponseStatuses;

/**
 * Description of FileUploadException
 *
 * @author Hristo
 */
class FileUploadException extends Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $code = ResponseStatuses::UNPROCESSABLE_ENTITY;
        $exceptionClass = basename(str_replace('\\', '/', get_class())) . '. ';
        parent::__construct($exceptionClass . $message, $code, $previous);
    }
}
