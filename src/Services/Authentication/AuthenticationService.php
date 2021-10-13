<?php

namespace App\Services\Authentication;

use App\Services\Authentication\Interfaces\AuthenticationInterface;
use App\Services\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Authentication\Interfaces\JwtHandlerInterface;
use App\Controllers\Input\Forms\SignInForm;
use App\Controllers\Input\Forms\SignUpForm;
use App\Models\User;
use App\Exceptions\NotFoundUserException;
use App\Exceptions\UserAuthenticationFailException;

/**
 * Description of AuthService
 *
 * @author Hristo
 */
class AuthenticationService implements AuthenticationInterface
{
    private UserRepositoryInterface $userRepository;
    private JwtHandlerInterface $jwtHandler;
    
    public function __construct(
        UserRepositoryInterface $userRepository,
        JwtHandlerInterface $jwtHandler
    )
    {
        $this->userRepository = $userRepository;
        $this->jwtHandler = $jwtHandler;
    }
    
    public function register(SignUpForm $input): User
    {
        return $this->userRepository->create($input);
    }
    
    /**
     * 
     * @param SignInForm $input
     * @return string Session Authentication Token.
     */
    public function login(SignInForm $input): string
    {
        $user = $this->userRepository->getByEmail($input->email);
        if ($user === null) {
            throw new NotFoundUserException('No user with email: '.$input->email.' registered.');
        }
        
        if (password_verify($input->password, $user->password_hash)) {
            $token = $this->jwtHandler->encodeJwtData(
                DOMAIN,
                array("user_id"=> $user->id),
            );

            return $token;
        }
        
        throw new UserAuthenticationFailException('Invalid JSON web token.');
    }
    
    public function getAuthorById(int $id): ?User
    {
        return $this->userRepository->getById($id);
    }
    
    public function getAuthorByEmail(string $email): ?User
    {
        return $this->userRepository->getByEmail($email);
    }
    
    /**
     * Validates that an user is logged.
     * Returns "User" object if yet logged or "null" if not.
     * 
     * @param string $bearerToken
     * @return User|null
     */
    public function getAuthenticatedUser(string $bearerToken): ?User
    {
        $bearerTokenArr = explode(' ', trim($bearerToken));
        $token = isset($bearerTokenArr[1]) && !empty(trim($bearerTokenArr[1])) ? trim($bearerTokenArr[1]) : '';
        $jwtData = $token === '' ? [] : $this->jwtHandler->decodeJwtdata(trim($token));
        if (isset($jwtData['auth']) && isset($jwtData['data']->user_id) && $jwtData['auth']) {
            $user = $this->userRepository->getById($jwtData['data']->user_id);
            return $user;
        }
        
        return null;
    }
    
    public function logout(User $user): void
    {
        // The easiest way with JWT is generation of a new token. Otherwise - the token should be saved into the database.
        $this->jwtHandler->encodeJwtData(
            DOMAIN,
            array("user_id"=> $user->id),
        );
    }
    
    /**
     * 
     * @return array User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->getAllOrderById();
    }
}
