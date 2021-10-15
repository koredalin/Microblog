<?php

namespace App\Controllers;

// Base controller
use App\Controllers\ApiBaseController;

// Request
use Psr\Http\Message\ServerRequestInterface;

// Services
use App\Services\User\Interfaces\UserInterface;

// Models
use App\Models\Input\SignUpForm;
use App\Models\Input\SignInForm;
use App\Models\User;

// Exceptions
use App\Exceptions\DtoValidationException;
use App\Exceptions\AlreadyExistingDbRecordException;
use App\Exceptions\NotFoundUserException;
use App\Exceptions\UserAuthenticationFailException;

// Response
use Psr\Http\Message\ResponseInterface;
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
            $signUpForm = SignUpForm::createSignUpForm(
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
        
        return $this->render(['message' => 'Successful registration.', 'user_id' => $author->id,], $args, ResponseStatuses::CREATED);
    }
    
    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        $requestBody = \json_decode($request->getBody()->getContents(), true);
        try {
            $signInForm = SignInForm::createSignInForm(
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
    
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->response = $response;
        
        try {
            $userForDeletionId = isset($args['id']) ? (int)$args['id'] : 0;
            $currentUser = $this->getAuthenticatedUser($request);
            if ($userForDeletionId !== $currentUser->id) {
                throw new WrongUserDeletionException('You can delete yourself only. User id: '.$userForDeletionId.'.');
            }
            $this->userService->delete($currentUser);
        } catch (UserAuthenticationFailException | WrongUserDeletionException | NotFoundUserException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResponseStatuses::INTERNAL_SERVER_ERROR;
            return $this->render(['message' => $ex->getMessage()], $args, $responseStatusCode);
        }
        
        return $this->render(['message' => 'User deleted.', 'user_id' => $currentUser->id,], $args);
    }
    
    private function getAuthenticatedUser(ServerRequestInterface $request): User
    {
        $authorizationHeaders = $request->getHeader('Authorization');
        $bearerToken = isset($authorizationHeaders[0]) ? trim($authorizationHeaders[0]) : '';
        $user = $this->userService->getAuthenticatedUser($bearerToken);
        if ($user === null) {
            throw new UserAuthenticationFailException('Not logged user. Please, log in again.');
        }
        
        return $user;
    }
}
