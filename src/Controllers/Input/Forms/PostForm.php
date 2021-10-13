<?php

namespace App\Controllers\Input\Forms;

use App\Models\Post;
use Psr\Http\Message\UploadedFileInterface;
use App\Exceptions\DtoValidationException;

/**
 * Description of BlogPostForm
 *
 * @author Hristo
 */
final class PostForm
{
    const ALLOWED_IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif',];
    const ALLOWED_IMAGE_EXTENSIONS = ['jpeg', 'jpg', 'png', 'gif',];
    const IMAGE_MAX_FILE_SIZE_MB = 3;
    const IMAGE_MAX_FILE_SIZE_B = 3145728;
    
    public string $title;
    public string $content;
    public UploadedFileInterface $uploadedImage;
    
    private function __construct()
    {
    }
    
    public static function create(
        ?string $title,
        ?string $content,
        UploadedFileInterface $uploadedImage
    ): self
    {
        $form = new self();
        $form->title = trim($title);
        $form->content = trim($content);
        $form->uploadedImage = $uploadedImage;
        
        return $form;
    }
    
    
    public function validate(): void
    {
        if (Post::TITLE_MIN_SYMBOLS > strlen($this->title) || Post::TITLE_MAX_SYMBOLS < strlen($this->title)) {
            throw new DtoValidationException('Blog post title should has '.Post::TITLE_MIN_SYMBOLS.' symbols min and '.Post::TITLE_MAX_SYMBOLS.' symbols max.');
        }
        
        if (Post::CONTENT_MIN_SYMBOLS > strlen($this->content)) {
            throw new DtoValidationException('Blog post content should has '.Post::CONTENT_MIN_SYMBOLS.' symbols min.');
        }
        
        if ($this->uploadedImage->getError() !== UPLOAD_ERR_OK) {
            throw new FileUploadException($this->uploadedImage->getError());
        }

        $uploadedFileMediaType = strtolower(trim($this->uploadedImage->getClientMediaType()));
        if (!in_array($uploadedFileMediaType, self::ALLOWED_IMAGE_MIME_TYPES, true)) {
            throw new FileUploadException('The uploaded image file is not an image.');
        }
        
        $uploadedFileExtension = pathinfo($this->uploadedImage->getClientFilename(), PATHINFO_EXTENSION);
        if (!in_array($uploadedFileExtension, self::ALLOWED_IMAGE_EXTENSIONS, true)) {
            throw new FileUploadException('The uploaded image file is not an image.');
        }

        if (self::IMAGE_MAX_FILE_SIZE_B < $this->uploadedImage->getSize()) {
            throw new FileUploadException('Too large file. Maximum upload size is '.self::IMAGE_MAX_FILE_SIZE_MB.' MB.');
        }
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function getContent(): string
    {
        return $this->content;
    }
    
    public function getUploadedImage(): UploadedFileInterface
    {
        return $this->uploadedImage;
    }
}