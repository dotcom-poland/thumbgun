<?php

declare(strict_types=1);

namespace App\Core\Source;

use App\Core\Image\ImageInterface;
use App\Core\Image\ImmutableImage;
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

    /** {@inheritDoc} */
    public function __invoke(string $imageId, string $imageFormat): ImageInterface
    {
        return new ImmutableImage($imageId, $imageFormat, function () use ($imageId): string {
            $object = $this->s3Client->getObject([
                'Key' => $imageId,
                'Bucket' => $this->s3Bucket,
            ]);

            /** @var StreamInterface $body */
            $body = $object['Body'];

            return $body->getContents();
        });
    }
}
