<?php

namespace App\Controllers;

use App\Controllers\ApiBaseController;
use App\Services\Authentication\Interfaces\UserInterface;
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
    private UserInterface $userService;
    
    public function __construct(
        UserInterface $userService
    )
    {
        $this->userService = $userService;
    }
    
    public function register(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        $requestBody = \json_decode($request->getBody()->getContents(), true);
        try {
            $signUpForm = SignUpForm::create(
                $requestBody['firstName'],
                $requestBody['lastName'],
                $requestBody['email'],
                $requestBody['password']
            );
            $signUpForm->validate();
            $author = $this->userService->register($signUpForm);
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
            $signInForm = SignInForm::create(
                $requestBody['email'],
                $requestBody['password']
            );
            $signInForm->validate();
            $author = $this->userService->getAuthorByEmail($signInForm->getEmail());
            $jwt = $this->userService->login($signInForm);
        } catch (DtoValidationException | AlreadyExistingDbRecordException | NotFoundUserException | UserAuthenticationFailException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Successful login', 'user_id' => $author->id, 'jwt' => $jwt,], $args);
    }
    
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        try {
            $allUsers = $this->userService->getAllOrderByIdPublic();
        } catch (Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'All users data.', 'result' => $allUsers,], $args);
    }
    
    public function view(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        try {
            $userId = isset($args['id']) ? (int)$args['id'] : 0;
            $user = $this->userService->getByIdPublic($userId);
            if (null === $user) {
                throw new NotFoundUserException('No user with id: '.$userId.'.');
            }
        } catch (NotFoundUserException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'Single user data.', 'result' => $user, 'user_id' => $userId,], $args);
    }
}
