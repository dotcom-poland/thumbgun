<?php

declare(strict_types=1);

namespace Test\App\Core\Filesystem;

use App\Core\Filesystem\Exception\FilesystemException;
use App\Core\Filesystem\FilesystemFileFactory;
use PHPUnit\Framework\TestCase;

final class FilesystemFileFactoryTest extends TestCase
{
    private readonly string $directory;
    private readonly FilesystemFileFactory $filesystem;

    protected function setUp(): void
    {
        $this->directory = \sys_get_temp_dir();
        $this->filesystem = new FilesystemFileFactory($this->directory);
    }

    public function testItCreatesFile(): void
    {
        $file = ($this->filesystem)('group/123.txt', 'data');

        self::assertFileExists(\sprintf('%s/group/123.txt', $this->directory));
        self::assertSame('data', $file->fgets());

        \unlink($file->getPathname());
    }

    public function testItPropagatesExceptionsIfFailedToCreate(): void
    {
        try {
            $this->expectException(FilesystemException::class);

            ($this->filesystem)('group/', 'data');
        } finally {
            \rmdir(\sprintf('%s/group', $this->directory));
        }
    }
}
