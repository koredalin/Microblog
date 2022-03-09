<?php

namespace App\Models\Input;

use App\Models\Post;
use Psr\Http\Message\UploadedFileInterface;
use App\Exceptions\DtoValidationException;
use App\Exceptions\FileUploadException;

/**
 * Description of BlogPostForm
 *
 * @author Hristo
 */
final class PostForm
{
    public const ALLOWED_IMAGE_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif',];
    public const ALLOWED_IMAGE_EXTENSIONS = ['jpeg', 'jpg', 'png', 'gif',];
    public const IMAGE_MAX_FILE_SIZE_MB = 3;
    public const IMAGE_MAX_FILE_SIZE_B = 3145728;

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
    ): self {
        $form = new self();
        $form->title = trim($title);
        $form->content = trim($content);
        $form->uploadedImage = $uploadedImage;

        return $form;
    }


    public function validate(): void
    {
        $this->validateTitle();
        $this->validateContent();
        $this->validateUploadedImage();
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

    private function validateTitle(): void
    {
        if (Post::TITLE_MIN_SYMBOLS > mb_strlen($this->title) || Post::TITLE_MAX_SYMBOLS < mb_strlen($this->title)) {
            throw new DtoValidationException('Blog post title should has ' . Post::TITLE_MIN_SYMBOLS
                . ' symbols min and ' . Post::TITLE_MAX_SYMBOLS . ' symbols max.');
        }
    }

    private function validateContent(): void
    {
        if (Post::CONTENT_MIN_SYMBOLS > mb_strlen($this->content)) {
            throw new DtoValidationException('Blog post content should has ' . Post::CONTENT_MIN_SYMBOLS
                . ' symbols min.');
        }
    }

    private function validateUploadedImage(): void
    {
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
            throw new FileUploadException('Too large file. Maximum upload size is '
                . self::IMAGE_MAX_FILE_SIZE_MB . ' MB.');
        }
    }
}
