<?php

namespace App\Services\Repositories\Interfaces;

use App\Models\User;
use App\Controllers\Input\Forms\SignUpForm;

/**
 *
 * @author Hristo
 */
interface UserRepositoryInterface
{
    public function create(SignUpForm $input): User;
    public function getById(int $id): ?User;
    public function getByEmail(string $email): ?User;
    public function getAllOrderById(): array;
    public function update(User $user): User;
    public function delete(User $user): bool;
}
