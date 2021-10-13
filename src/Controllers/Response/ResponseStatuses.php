<?php

namespace App\Controllers\Response;

final class ResponseStatuses
{
    const SUCCESS = 200;
    const CREATED = 201;
    const ALREADY_REPORTED = 208;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const UNPROCESSABLE_ENTITY = 422;
    const INTERNAL_SERVER_ERROR = 500;
    const SERVICE_UNAVAILABLE = 503;
}