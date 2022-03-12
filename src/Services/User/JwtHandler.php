<?php

namespace App\Services\User;

use App\Services\User\Interfaces\JwtHandlerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

/**
 * JSON Web Token
 * https://bg.wikipedia.org/wiki/JSON_Web_Token
 *
 * Description of JwtHandler
 *
 * @author Hristo
 */
class JwtHandler implements JwtHandlerInterface
{
    protected $jwtSecrect;
    protected $token;
    protected $issuedAt;
    protected $expire;
    protected $jwt;

    public function __construct()
    {
        // set your default time-zone
        date_default_timezone_set('Europe/Sofia');
        $this->issuedAt = time();

        $this->expire = $this->issuedAt + SESSION_DURATION_IN_SECONDS;

        // Set your secret or signature
        $this->jwtSecrect = "Microblog post secrect";
    }

    // ENCODING THE TOKEN
    public function encodeJwtData(string $issuer, array $data): string
    {
        $this->token = array(
            //Adding the identifier to the token (who issue the token)
            "iss" => $issuer,
            "aud" => $issuer,
            // Adding the current timestamp to the token, for identifying that when the token was issued.
            "iat" => $this->issuedAt,
            // Token expiration
            "exp" => $this->expire,
            // Payload
            "data" => $data
        );
        $this->jwt = JWT::encode($this->token, $this->jwtSecrect);

        return $this->jwt;
    }

    //DECODING THE TOKEN
    public function decodeJwtdata(string $jwt): array
    {
        try {
            $decode = JWT::decode($jwt, $this->jwtSecrect, array('HS256'));
            return [
                "auth" => 1,
                "data" => $decode->data
            ];
        } catch (ExpiredException $e) {
            return $this->errMsg($e->getMessage());
        } catch (SignatureInvalidException $e) {
            return $this->errMsg($e->getMessage());
        } catch (BeforeValidException $e) {
            return $this->errMsg($e->getMessage());
        } catch (\DomainException $e) {
            return $this->errMsg($e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return $this->errMsg($e->getMessage());
        } catch (\UnexpectedValueException $e) {
            return $this->errMsg($e->getMessage());
        }
    }

    protected function errMsg($msg)
    {
        return [
            "auth" => 0,
            "message" => $msg
        ];
    }
}
