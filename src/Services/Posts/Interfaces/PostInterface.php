<?php

namespace App\Services\Posts\Interfaces;

use App\Models\User;
use App\Models\Post;
use App\Models\Input\PostForm;

/**
 *
 * @author Hristo
 */
interface PostInterface
{
    /**
     * Making a new post record into the database.
     * The new post id is used for the new image name.
     *
     * @param User $user
     * @param PostForm $postForm
     * @return Post
     */
    public function create(User $user, PostForm $postForm): Post;

    /**
     * Returns a Post model by post id or null, if no match.
     *
     * @param int $id
     * @return Post|null
     */
    public function getById(int $id): ?Post;

    /**
     * Returns a Post model by user id and post title or null, if no match.
     *
     * @param int $userId
     * @param string $title
     * @return Post|null
     */
    public function getByUserIdTitle(int $userId, string $title): ?Post;

    /**
     *
     * @return array Post[]
     */
    public function getAllOrderById(): array;

    /**
     * Updates a post record into the database.
     * The post id is used for the new image name.
     *
     * @param PostForm $postForm
     * @return Post
     */
    public function update(PostForm $postForm, Post $post): Post;

    /**
     *
     * @param int $postId
     * @return void
     */
    public function delete(int $postId): void;
}
