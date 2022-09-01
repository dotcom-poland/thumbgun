<?php

namespace Test\App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Source\AWSS3ImageSource;
use App\Core\Source\Exception\ImageSourceException;
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

    public function testItCreatesImageFromResponse(): ImageInterface
    {
        $body = new BufferStream();
        $body->write('test');

        $this->s3Client->method('getObject')
            ->with([
                'Key' => 'a/b/c.jpeg',
                'Bucket' => 's3-bucket',
            ])
            ->willReturn(new Result([
                'ContentLength' => (int) $body->getSize(),
                'Body' => $body,
            ]));

        $result = ($this->source)('a/b/c.jpeg', 'webp');

        $this->expectNotToPerformAssertions();

        return $result;
    }

    /** @depends testItCreatesImageFromResponse */
    public function testResponseHasProperId(ImageInterface $image): void
    {
        self::assertSame('a/b/c.jpeg', $image->getImageId());
    }

    /** @depends testItCreatesImageFromResponse */
    public function testResponseHasProperRequestedImageFormat(ImageInterface $image): void
    {
        self::assertSame('webp', $image->getRequestedFormat());
    }

    /** @depends testItCreatesImageFromResponse */
    public function testResponseHasProperImageContent(ImageInterface $image): void
    {
        self::assertSame('test', $image->getSource()->fgets());
    }

    public function testItWrapsException(): void
    {
        $this->s3Client->method('getObject')
            ->willThrowException(new \Exception());

        $this->expectException(ImageSourceException::class);

        ($this->source)('a/b/c.jpeg', 'webp');
    }
}
