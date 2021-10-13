<?php

namespace App\Services\Author\Interfaces;

use App\Controllers\Input\Forms\SignUpForm;
use App\Controllers\Input\Forms\SignInForm;
use App\Models\User;

/**
 *
 * @author Hristo
 */
interface AuthorAuthenticationInterface
{
    public function register(SignUpForm $input): User;
    
    /**
     * 
     * @param SignInForm $input
     * @return string Session Authentication Token.
     */
    public function login(SignInForm $input): string;
    
    /**
     * Validates that an user is logged.
     * Returns "User" object if yet logged or "null" if not.
     * 
     * @param string $jwt - JSON web token.
     * @return User|null
     */
    public function getAuthenticatedUser(string $jwt): ?User;
    
    public function logout(SignInForm $input): User;
    
    /**
     * 
     * @return array User[]
     */
    public function getAll(): array;
}
