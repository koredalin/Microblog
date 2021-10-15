<?php

namespace App\Services\Repositories\Interfaces;

use App\Models\User;
use App\Models\Post;
use App\Models\Input\PostForm;

/**
 *
 * @author Hristo
 */
interface PostRepositoryInterface
{
    public function create(User $user, PostForm $input): Post;
    
    public function getById(int $id): ?Post;
    
    public function getByUserIdTitle(int $userId, string $title): ?Post;
    
    /**
     * 
     * @return array Post[]
     */
    public function getAllOrderById(): array;
    
    public function update(Post $post): Post;
    
    public function delete(Post $post): void;
}
