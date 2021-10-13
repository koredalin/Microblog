<?php

namespace App\Controllers\Input\Forms;

use App\Models\Post;

/**
 * Description of BlogPostForm
 *
 * @author Hristo
 */
class PostForm
{
    public string $title;
    public string $content;
    public string $clientImageFileName;
    
    
    public function validate(): void
    {
        $this->trimAllData();
        
        if (Post::TITLE_MIN_SYMBOLS > strlen($this->title) || Post::TITLE_MAX_SYMBOLS < strlen($this->title)) {
            throw new DtoValidationException('Blog post title should has '.Post::TITLE_MIN_SYMBOLS.' symbols min and '.Post::TITLE_MAX_SYMBOLS.' symbols max.');
        }
        
        if (Post::CONTENT_MIN_SYMBOLS > strlen($this->content)) {
            throw new DtoValidationException('Blog post content should has '.Post::CONTENT_MIN_SYMBOLS.' symbols min.');
        }
    }
    
    private function trimAllData(): void
    {
        $this->title = trim($this->title);
        $this->content = trim($this->content);
        $this->clientImageFileName = trim($this->clientImageFileName);
    }
}
