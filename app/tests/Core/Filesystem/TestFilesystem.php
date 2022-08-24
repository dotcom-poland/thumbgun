<?php

declare(strict_types=1);

namespace Test\App\Core\Filesystem;

use App\Core\Filesystem\FilesystemInterface;

final class TestFilesystem implements FilesystemInterface
{
    /** {@inheritDoc} */
    public function createFile(string $filename): \SplFileObject
    {
        return new \SplTempFileObject();
    }
}
