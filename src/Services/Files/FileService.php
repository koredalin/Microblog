<?php

namespace App\Services\Files;

use App\Services\Files\Interfaces\FileInterface;
use App\Exceptions\NotDeletedFileException;

/**
 * Description of ImageUploadService
 *
 * @author Hristo
 */
class FileService implements FileInterface
{
    /**
     * 
     * @param string $directory
     * @param string $fileFullName
     * @return void
     */
    public function delete(string $directory, string $fileFullName): void
    {
        if (!unlink($directory.DIRECTORY_SEPARATOR.$fileFullName)) {
            throw new NotDeletedFileException('Not deleted file.');
        }
    }
}