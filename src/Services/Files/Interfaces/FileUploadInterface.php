<?php

namespace App\Services\Files\Interfaces;

use Psr\Http\Message\UploadedFileInterface;

/**
 *
 * @author Hristo
 */
interface FileUploadInterface
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
    public function moveUploadedFile(
        string $directory,
        UploadedFileInterface $uploadedFile,
        string $fileBaseName
    ): string;
}
