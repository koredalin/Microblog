<?php

namespace App\Services\Authentication\Interfaces;

use App\Controllers\Input\Forms\SignUpForm;
use App\Controllers\Input\Forms\SignInForm;
use App\Models\User;

/**
 *
 * @author Hristo
 */
interface AuthenticationInterface
{
    public function register(SignUpForm $input): User;
    
    /**
     * 
     * @param SignInForm $input
     * @return string Session Authentication Token.
     */
    public function login(SignInForm $input): string;
    
    public function getAuthorById(int $id): ?User;
    
    public function getAuthorByEmail(string $email): ?User;
    
    /**
     * Validates that an user is logged.
     * Returns "User" object if yet logged or "null" if not.
     * 
     * @param string $bearerToken
     * @return User|null
     */
    public function getAuthenticatedUser(string $bearerToken): ?User;
    
    public function logout(User $user): void;
    
    /**
     * 
     * @return array User[]
     */
    public function getAll(): array;
}
