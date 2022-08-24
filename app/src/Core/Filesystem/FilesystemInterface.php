<?php

namespace App\Core\Filesystem;

use App\Core\Filesystem\Exception\FilesystemException;

interface FilesystemInterface
{
    /** @throws FilesystemException */
    public function createFile(string $filename): \SplFileObject;
}
