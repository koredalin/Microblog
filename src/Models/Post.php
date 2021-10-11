<?php

namespace App\Models;

/**
 * Description of Post
 *
 * @author Hristo
 */
class Post
{
    const IMAGES_DIR = '/public/images/';
    
    public int $id;
    public int $user_id;
    public string $title;
    public string $content;
    public string $image_file_path;
    public string $created_at;
    public string $updated_at;
    
    
    public function validate(): void
    {
        $this->trimAllData();
        
        if (!isset($this->user_id) || 1 > $this->user_id) {
            throw new DtoValidationException('User id is not set.');
        }
        
        if (3 > strlen($this->title) || 255 < strlen($this->title)) {
            throw new DtoValidationException('Blog post title should has 10 symbols min.');
        }
        
        if (10 < strlen($this->content)) {
            throw new DtoValidationException('Blog post content should has 10 symbols min.');
        }
        
        if (10 < strlen($this->image_file_path)) {
            throw new DtoValidationException('Image file path has 10 symbols min.');
        }
        
        if (DateTimeManager::validateDateTime($this->created_at)) {
            throw new DtoValidationException('Not valid blog post creation date format.');
        }
        
        if (DateTimeManager::validateDateTime($this->updated_at)) {
            throw new DtoValidationException('Not valid blog post update date format.');
        }
    }
    
    public function validateDbRecord(): void
    {
        $this->validate();
        
        if (!isset($this->id) || 1 > $this->id) {
            throw new DtoValidationException('Blog post id is not set.');
        }
    }
    
    private function trimAllData(): void
    {
        $this->title = trim($this->title);
        $this->content = trim($this->content);
        $this->image_file_path = trim($this->image_file_path);
        $this->created_at = trim($this->created_at);
        $this->updated_at = trim($this->updated_at);
    }
}