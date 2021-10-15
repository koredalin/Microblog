<?php

namespace App\Services\Repositories;

// Implementation
use App\Services\Repositories\Interfaces\PostRepositoryInterface;
// Database
use PDO;
// Input forms
use App\Models\Input\PostForm;
// Models
use App\Models\User;
use App\Models\Post;
// Helpers
use App\Services\Helpers\DateTimeManager;
// Exceptions
use Exception;
use App\Exceptions\AlreadyExistingDbRecordException;

/**
 * Description of PostRepository
 *
 * @author Hristo
 */
class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function create(User $user, PostForm $input): Post
    {
        $user->validateDbRecord();
        
        $post = new Post();
        $post->user_id = $user->id;
        $post->title = $input->getTitle();
        $post->content = $input->getContent();
        $post->image_file_name = '';
        $post->created_at = DateTimeManager::nowStr();
        $post->updated_at = DateTimeManager::nowStr();
        
        $post->validate();
        
        if (null !== $this->getByUserIdTitle($user->id, $post->title)) {
            throw new AlreadyExistingDbRecordException('There is a blog post with this title for author: '.$user->email.'.');
        }
        
        $insert_query = "INSERT INTO `post` (`user_id`, `title`, `content`, `image_file_name`, `created_at`, `updated_at`)
            VALUES (:user_id, :title, :content, :image_file_name, :created_at, :updated_at)";

        $insert_stmt = $this->dbConnection->prepare($insert_query);

        // DATA BINDING
        $insert_stmt->bindValue(':user_id', $post->user_id, PDO::PARAM_INT);
        $insert_stmt->bindValue(':title', htmlspecialchars(strip_tags($post->title)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':content', htmlspecialchars($post->content), PDO::PARAM_STR);
        $insert_stmt->bindValue(':image_file_name', $post->image_file_name, PDO::PARAM_STR);
        $insert_stmt->bindValue(':created_at', $post->created_at, PDO::PARAM_STR);
        $insert_stmt->bindValue(':updated_at', $post->updated_at, PDO::PARAM_STR);
        
        $insert_stmt->execute();
        
        $post->id = $this->dbConnection->lastInsertId();
        
        $post->validateDbRecord();
        
        return $post;
    }
    
    public function getById(int $id): ?Post
    {
        $sql = "SELECT * FROM `post` WHERE id={$id}";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchObject(Post::class);
        }
        
        return null;
    }
    
    public function getByUserIdTitle(int $userId, string $title): ?Post
    {
        $check_email = "SELECT * FROM `post` WHERE `user_id`=:user_id AND `title`=:title";
        $stmt = $this->dbConnection->prepare($check_email);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':title', trim($title), PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchObject(Post::class);
        }
        
        return null;
    }
    
    /**
     * 
     * @return array Post[]
     */
    public function getAllOrderById(): array
    {
        $sql = "SELECT * FROM `post` ORDER BY `id`";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, Post::class);
        }
    
        return [];
    }
    
    public function update(Post $post): Post
    {
        $post->validateDbRecord();

        $update_query = "UPDATE `post` SET
            user_id = :user_id,
            title = :title,
            content = :content,
            image_file_name = :image_file_name,
            updated_at = :updated_at 
        WHERE id = :id";

        $update_stmt = $this->dbConnection->prepare($update_query);

        $update_stmt->bindValue(':user_id', $post->user_id, PDO::PARAM_INT);
        $update_stmt->bindValue(':title', htmlspecialchars(strip_tags($post->title)), PDO::PARAM_STR);
        $update_stmt->bindValue(':content', htmlspecialchars($post->content), PDO::PARAM_STR);
        $update_stmt->bindValue(':image_file_name', $post->image_file_name, PDO::PARAM_STR);
        $update_stmt->bindValue(':updated_at', $post->updated_at, PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $post->id, PDO::PARAM_INT);


        if ($update_stmt->execute()) {
            return $post;
        }
        
        throw new Exception('Not updated Post record id: '.$post->id.'.');
    }
    
    public function delete(Post $post): void
    {
        $post->validateDbRecord();
        
        // No such DB User record.
        if (null === $this->getById($post->id)) {
            return;
        }

        $delete_post = "DELETE FROM `post` WHERE id=:id";
        $deleteStmt = $this->dbConnection->prepare($delete_post);
        $deleteStmt->bindValue(':id', $post->id, PDO::PARAM_INT);

        if (!$deleteStmt->execute()) {
            throw new Exception('Not deleted Post record id: '.$post->id.'.');
        }
    }
}