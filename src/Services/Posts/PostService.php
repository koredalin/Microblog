<?php

namespace App\Services\Posts;

use App\Services\Posts\Interfaces\PostInterface;
use App\Services\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Files\Interfaces\FileUploadInterface;
use App\Services\Files\Interfaces\FileInterface;
// Input forms
use App\Models\Input\PostForm;
// Models
use App\Models\User;
use App\Models\Post;
use App\Services\Helpers\DateTimeManager;
// Exceptions
use App\Exceptions\NotFoundPostException;

/**
 * Description of PostService
 *
 * @author Hristo
 */
class PostService implements PostInterface
{
    private UserRepositoryInterface $userRepository;
    private PostRepositoryInterface $postRepository;
    private FileUploadInterface $fileUpload;
    private FileInterface $file;
    
    public function __construct(
        UserRepositoryInterface $userRepository,
        PostRepositoryInterface $postRepository,
        FileUploadInterface $fileUpload,
        FileInterface $file
    )
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->fileUpload = $fileUpload;
        $this->file = $file;
    }
    
    
    public function create(User $user, PostForm $input): Post
    {
        // Creating a Post record into the database.
        $post = $this->postRepository->create($user, $input);
        
        return $this->moveTheUploadedFileAndUpdatePostTable($input, $post);
    }
    
    public function getById(int $id): ?Post
    {
        return $this->postRepository->getById($id);
    }
    
    public function getByUserIdTitle(int $userId, string $title): ?Post
    {
        return $this->postRepository->getByUserIdTitle($userId, $title);
    }
    
    /**
     * 
     * @return array Post[]
     */
    public function getAllOrderById(): array
    {
        return $this->postRepository->getAllOrderById();
    }
    
    public function update(User $user, PostForm $input, Post $post): Post
    {
        $post->title = $input->title;
        $post->content = $input->content;
        
        return $this->moveTheUploadedFileAndUpdatePostTable($input, $post);
    }
    
    public function delete(int $postId): void
    {
        $post = $this->getById($postId);
        if ($post === null) {
            throw new NotFoundPostException('Not found blog post. Nothing for deletion.');
        }
        
        $this->file->delete(IMAGES_UPLOAD_DIR, $post->image_file_name);
        $this->postRepository->delete($post);
    }
    
    private function moveTheUploadedFileAndUpdatePostTable(PostForm $input, Post $post): Post
    {
        // Move the uploaded file to public images directory.
        $imageFileBaseName = Post::IMAGE_FILE_NAME_PREFIX.$post->id;
        $finalFileName = $this->fileUpload->moveUploadedFile(IMAGES_UPLOAD_DIR, $input->getUploadedImage(), $imageFileBaseName);
        
        // Deletes the old file, if it is with another extension.
        if (trim($post->image_file_name) !== '' && $finalFileName !== $post->image_file_name) {
            $this->file->delete(IMAGES_UPLOAD_DIR, $post->image_file_name);
        }
        
        // Update the post.image_file_name into the database.
        $post->image_file_name = $finalFileName;
        $post->updated_at = DateTimeManager::nowStr();
        $postWithImageFileName = $this->postRepository->update($post);
        
        return $postWithImageFileName;
    }
}
