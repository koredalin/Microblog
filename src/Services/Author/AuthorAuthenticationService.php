<?php

namespace App\Services\Author;

use App\Services\Author\Interfaces\AuthorAuthenticationInterface;
use App\Services\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Authentication\Interfaces\JwtHandlerInterface;
use App\Controllers\Input\Forms\SignInForm;
use App\Controllers\Input\Forms\SignInForm;
use App\Models\User;
use App\Exceptions\NotFoundUserException;
use App\Exceptions\UserAuthenticationFailException;

/**
 * Description of AuthService
 *
 * @author Hristo
 */
class AuthorAuthenticationService implements AuthorAuthenticationInterface
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
            $jwt = new JwtHandler();
            $token = $this->jwtHandler->encodeJwtData(
                DOMAIN,
                array("user_id"=> $user->id),
            );

            return $token;
        }
        
        throw new UserAuthenticationFailException('Invalid JSON web token.');
    }
    
    public function logout(SignInForm $input): User
    {
        
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
