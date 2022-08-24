<?php

declare(strict_types=1);

namespace Test\App\Core\Filesystem;

use App\Core\Filesystem\Exception\FilesystemException;
use App\Core\Filesystem\TemporaryFilesystem;
use PHPUnit\Framework\TestCase;

final class TemporaryFilesystemTest extends TestCase
{
    private readonly string $directory;
    private readonly TemporaryFilesystem $filesystem;

    protected function setUp(): void
    {
        $this->directory = \sys_get_temp_dir();
        $this->filesystem = new TemporaryFilesystem($this->directory);
    }

    public function testItCreatesFile(): void
    {
        $file = $this->filesystem->createFile('group/123.txt');

        self::assertFileExists(\sprintf('%s/group/123.txt', $this->directory));

        \unlink($file->getPathname());
    }

    public function testItPropagatesExceptionsIfFailedToCreate(): void
    {
        $this->expectException(FilesystemException::class);

        $this->filesystem->createFile('group/');
    }
}
