<?php

namespace App\Controllers;

use App\Controllers\ApiBaseController;
use App\Services\Authentication\Interfaces\AuthenticationInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controllers\Input\Forms\SignUpForm;
use App\Controllers\Input\Forms\SignInForm;
use App\Exceptions\DtoValidationException;
use App\Exceptions\AlreadyExistingDbRecordException;
use App\Exceptions\NotFoundUserException;
use App\Exceptions\UserAuthenticationFailException;
use App\Controllers\Response\ResponseStatuses;

/**
 * Description of AuthorController
 *
 * @author Hristo
 */
class UserController extends ApiBaseController
{
    private AuthenticationInterface $authentication;
    
    public function __construct(
        AuthenticationInterface $authorAuth
    )
    {
        $this->authentication = $authorAuth;
    }
    
    public function register(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        $requestBody = \json_decode($request->getBody()->getContents(), true);
        try {
            $signUpForm = new SignUpForm();
            $signUpForm->firstName = $requestBody['firstName'];
            $signUpForm->lastName = $requestBody['lastName'];
            $signUpForm->email = $requestBody['email'];
            $signUpForm->password = $requestBody['password'];
            $signUpForm->validate();
            $author = $this->authentication->register($signUpForm);
        } catch (DtoValidationException | AlreadyExistingDbRecordException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Successful registration', 'user_id' => $author->id,], $args, ResponseStatuses::CREATED);
    }
    
    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        $requestBody = \json_decode($request->getBody()->getContents(), true);
        try {
            $signInForm = new SignInForm();
            $signInForm->email = $requestBody['email'];
            $signInForm->password = $requestBody['password'];
            $signInForm->validate();
            $author = $this->authentication->getAuthorByEmail($signInForm->email);
            $jwt = $this->authentication->login($signInForm);
        } catch (DtoValidationException | AlreadyExistingDbRecordException | NotFoundUserException | UserAuthenticationFailException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Successful login', 'user_id' => $author->id, 'jwt' => $jwt,], $args);
    }
    
    public function logout(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        $authorizationHeaders = $request->getHeader('Authorization');
        try {
            $bearerToken = isset($authorizationHeaders[0]) ? trim($authorizationHeaders[0]) : '';
            $user = $this->authentication->getAuthenticatedUser($bearerToken);
            if ($user === null) {
                throw new NotFoundUserException('Logout failed. No such user.');
            }
            $this->authentication->logout($user);
        } catch (NotFoundUserException | UserAuthenticationFailException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Successful logout', 'user_id' => $user->id,], $args);
    }
}
