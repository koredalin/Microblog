<?php

namespace App\Services\Authentication\Interfaces;

/**
 * JSON Web Token
 * https://bg.wikipedia.org/wiki/JSON_Web_Token
 * 
 * @author Hristo
 */
interface JwtHandlerInterface
{
    public function encodeJwtData(string $issuer, array $data): string;
    
    public function decodeJwtdata(string $jwt): array;
}
