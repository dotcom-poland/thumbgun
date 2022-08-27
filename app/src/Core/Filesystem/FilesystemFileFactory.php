<?php

declare(strict_types=1);

namespace App\Core\Filesystem;

use App\Core\Filesystem\Exception\FilesystemException;

final class FilesystemFileFactory implements FileFactory
{
    public function __construct(
        private readonly string $directory,
    ) {}

    /** {@inheritDoc} */
    public function __invoke(string $filename, string $data): \SplFileObject
    {
        $pathname = \sprintf('%s/%s', $this->directory, $filename);
        $dirname = \dirname($pathname);

        if (false === \is_dir($dirname)) {
            if (false === \mkdir($dirname, 0777, true)) {
                throw new FilesystemException('Could not create temporary directory');
            }
        }

        try {
            \touch($pathname);

            $file = new \SplFileObject($pathname, 'w+');
            $file->fwrite($data);
            $file->fseek(0);

            \clearstatcache(filename: $pathname);

            return $file;
        } catch (\Exception $exception) {
            throw new FilesystemException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
}
