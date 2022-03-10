<?php

namespace App\Controllers\Response;

final class ResponseStatuses
{
    public const SUCCESS = 200;
    public const CREATED = 201;
    public const ALREADY_REPORTED = 208;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const UNPROCESSABLE_ENTITY = 422;
    public const INTERNAL_SERVER_ERROR = 500;
    public const SERVICE_UNAVAILABLE = 503;
}
