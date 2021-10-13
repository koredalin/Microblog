<?php

namespace App\Services\Authentication\Interfaces;

use App\Controllers\Input\Forms\SignUpForm;
use App\Controllers\Input\Forms\SignInForm;
use App\Models\User;

/**
 *
 * @author Hristo
 */
interface UserInterface
{
    public function register(SignUpForm $input): User;
    
    /**
     * 
     * @param SignInForm $input
     * @return string Session Authentication Token.
     */
    public function login(SignInForm $input): string;
    
    public function getById(int $id): ?User;
    
    /**
     * Returns the data for a single user, but without password_hash column.
     * 
     * @return User
     */
    public function getByIdPublic(int $id): ?User;
    
    /**
     * Returns all the data from user table, but without password_hash column.
     * 
     * @return array User[]
     */
    public function getAllOrderByIdPublic(): array;
    
    public function getAuthorByEmail(string $email): ?User;
    
    /**
     * Validates that an user is logged.
     * Returns "User" object if yet logged or "null" if not.
     * 
     * @param string $bearerToken
     * @return User|null
     */
    public function getAuthenticatedUser(string $bearerToken): ?User;
}
