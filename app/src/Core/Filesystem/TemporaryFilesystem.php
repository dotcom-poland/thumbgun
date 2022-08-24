<?php

declare(strict_types=1);

namespace App\Core\Filesystem;

use App\Core\Filesystem\Exception\FilesystemException;

final class TemporaryFilesystem implements FilesystemInterface
{
    public function __construct(
        private readonly string $directory,
    )
    {
    }

    /** {@inheritDoc} */
    public function createFile(string $filename): \SplFileObject
    {
        $pathname = \sprintf('%s/%s', $this->directory, $filename);
        $dirname = \dirname($pathname);

        if (false === \is_dir($dirname)) {
            if (false === \mkdir($dirname, 0777, true)) {
                throw new FilesystemException('Could not create temporary directory');
            }
        }

        try {
            if (false === \file_exists($pathname)) {
                \touch($pathname);
            }

            return new \SplFileObject($pathname, 'w+');
        } catch (\Exception $exception) {
            throw new FilesystemException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
}
