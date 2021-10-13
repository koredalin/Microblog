<?php

namespace App\Models;

// Helpers
use App\Services\Helpers\DateTimeManager;
// Exceptions
use App\Exceptions\DtoValidationException;

/**
 * Description of Post
 *
 * @author Hristo
 */
class Post
{
    const TITLE_MIN_SYMBOLS = 3;
    const TITLE_MAX_SYMBOLS = 255;
    const CONTENT_MIN_SYMBOLS = 10;
    const IMAGE_FILE_NAME_MIN_SYMBOLS = 6;
    const IMAGE_FILE_NAME_PREFIX = 'image';
    
    public int $id;
    public int $user_id;
    public string $title;
    public string $content;
    public string $image_file_name;
    public string $created_at;
    public string $updated_at;
    
    
    public function validate(): void
    {
        $this->trimAllData();
        
        if (!isset($this->user_id) || 1 > $this->user_id) {
            throw new DtoValidationException('User id is not set.');
        }
        
        if (self::TITLE_MIN_SYMBOLS > strlen($this->title) || self::TITLE_MAX_SYMBOLS < strlen($this->title)) {
            throw new DtoValidationException('Blog post title should has '.self::TITLE_MIN_SYMBOLS.' symbols min and '.self::TITLE_MAX_SYMBOLS.' symbols max.');
        }
        
        if (self::CONTENT_MIN_SYMBOLS > strlen($this->content)) {
            throw new DtoValidationException('Blog post content should has '.self::CONTENT_MIN_SYMBOLS.' symbols min.');
        }
        
        if (!DateTimeManager::isValidDateTime($this->created_at)) {
            throw new DtoValidationException('Not valid blog post creation date format.');
        }
        
        if (!DateTimeManager::isValidDateTime($this->updated_at)) {
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
        $this->image_file_name = trim($this->image_file_name);
        $this->created_at = trim($this->created_at);
        $this->updated_at = trim($this->updated_at);
    }
}