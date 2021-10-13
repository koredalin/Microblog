<?php

namespace App\Services\Files\Interfaces;

/**
 *
 * @author Hristo
 */
interface FileInterface
{
    /**
     * 
     * @param string $directory
     * @param string $fileFullName
     * @return void
     */
    public function delete(string $directory, string $fileFullName): void;
}
