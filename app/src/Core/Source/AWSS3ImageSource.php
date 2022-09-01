<?php

declare(strict_types=1);

namespace App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
use App\Core\Source\Exception\ImageSourceException;
use Aws\S3\S3Client;
use Psr\Http\Message\StreamInterface;

final class AWSS3ImageSource implements ImageSourceInterface
{
    private S3Client $s3Client;
    private string $s3Bucket;

    public function __construct(S3Client $s3Client, string $s3Bucket)
    {
        $this->s3Client = $s3Client;
        $this->s3Bucket = $s3Bucket;
    }

    public function __invoke(string $imageId, string $imageFormat): ImageInterface
    {
        try {
            $object = $this->s3Client->getObject([
                'Key' => $imageId,
                'Bucket' => $this->s3Bucket,
            ]);
        } catch (\Exception $exception) {
            throw new ImageSourceException(
                \sprintf('S3 exception: %s', $exception->getMessage()),
                (int) $exception->getCode(),
                $exception,
            );
        }

        /** @var int $contentLength */
        $contentLength = $object['ContentLength'];

        /** @var StreamInterface $body */
        $body = $object['Body'];

        $imageFile = new \SplTempFileObject($contentLength);
        $imageFile->fwrite($body->getContents());
        $imageFile->fseek(0);

        return new ImmutableImage($imageId, $imageFormat, $imageFile);
    }
}
