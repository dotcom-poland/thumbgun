<?php

declare(strict_types=1);

namespace Test\App\Core\Filesystem;

use App\Core\Filesystem\FileFactory;

final class TestFileFactory implements FileFactory
{
    /** {@inheritDoc} */
    public function __invoke(string $filename, string $data): \SplFileObject
    {
        $file = new \SplTempFileObject();
        $file->fwrite($data);
        $file->fflush();
        $file->fseek(0);

        return $file;
    }
}
