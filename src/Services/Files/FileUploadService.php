<?php

namespace App\Services\Files;

use App\Services\Files\Interfaces\FileUploadInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Description of ImageUploadService
 *
 * @author Hristo
 */
class FileUploadService implements FileUploadInterface
{
    /**
     * Moves the uploaded file to the upload directory and assigns it a custom name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string $directory The directory to which the file is moved
     * @param UploadedFileInterface $uploadedFile The file uploaded file to move
     * @param string $fileBaseName The file name without the extension.
     *
     * @return string The filename of moved file
     */
    public function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile, string $fileBaseName): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $fileName = $fileBaseName.'.'.$extension;
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);

        return $fileName;
    }
}