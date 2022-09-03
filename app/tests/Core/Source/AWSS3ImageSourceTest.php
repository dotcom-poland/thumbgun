<?php

namespace Test\App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Source\AWSS3ImageSource;
use Aws\Result;
use Aws\S3\S3Client;
use GuzzleHttp\Psr7\BufferStream;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class AWSS3ImageSourceTest extends TestCase
{
    private readonly S3Client|MockObject $s3Client;
    private readonly AWSS3ImageSource $source;

    protected function setUp(): void
    {
        $this->s3Client = $this->getMockBuilder(S3Client::class)
            ->disableOriginalConstructor()
            ->addMethods(['getObject'])
            ->getMock();

        $this->source = new AWSS3ImageSource($this->s3Client, 's3-bucket');
    }

    public function testResponseHasProperId(): void
    {
        $image = ($this->source)('a/b/c.jpeg', 'webp');

        self::assertSame('a/b/c.jpeg', $image->getImageId());
    }

    public function testResponseHasProperRequestedImageFormat(): void
    {
        $image = ($this->source)('a/b/c.jpeg', 'webp');

        self::assertSame('webp', $image->getRequestedFormat());
    }

    public function testResponseHasProperImageContent(): void
    {
        $image = ($this->source)('a/b/c.jpeg', 'webp');

        $body = new BufferStream();
        $body->write('test');

        $this->s3Client->method('getObject')
            ->with([
                'Key' => 'a/b/c.jpeg',
                'Bucket' => 's3-bucket',
            ])
            ->willReturn(new Result([
                'Body' => $body,
            ]));

        self::assertSame('test', $image->getSource()());
    }
}
