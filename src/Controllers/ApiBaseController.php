<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use App\Controllers\Response\ResponseStatuses;

/**
 * Description of ApiBaseController
 *
 * @author Hristo
 */
class ApiBaseController
{
    protected ResponseInterface $response;
    
    public function render(array $responseResult, array $arguments, int $status = ResponseStatuses::SUCCESS): ResponseInterface
    {
        $result = [
            'response' => $responseResult,
            'arguments' => $arguments,
            'response_http_status' => $status,
        ];
        $this->response->getBody()->write(
            \json_encode($result)
        );
        
        return $this->response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
