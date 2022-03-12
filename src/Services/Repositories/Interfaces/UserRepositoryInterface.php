<?php

namespace App\Services\Repositories\Interfaces;

use App\Models\User;
use App\Models\Input\SignUpForm;

/**
 *
 * @author Hristo
 */
interface UserRepositoryInterface
{
    public function create(SignUpForm $input): User;
    public function getById(int $id): ?User;
    public function getByEmail(string $email): ?User;

    /**
     * Returns all the data from user table, but without password_hash column.
     *
     * @return array User[]
     */
    public function getAllOrderByIdPublic(): array;
    public function update(User $user): User;
    public function delete(User $user): void;
}
