<?php

namespace App\Core\Filesystem;

use App\Core\Filesystem\Exception\FilesystemException;

interface FileFactory
{
    /** @throws FilesystemException */
    public function __invoke(string $filename, string $data): \SplFileObject;
}
