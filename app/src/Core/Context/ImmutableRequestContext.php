<?php

declare(strict_types=1);

namespace App\Core\Context;

use App\Core\RequestContextInterface;

/**
 * @psalm-immutable
 */
final class ImmutableRequestContext implements RequestContextInterface
{
    /** @var non-empty-string */
    private readonly string $checksum;

    /** @var non-empty-string */
    private readonly string $strategyName;

    /** @var non-empty-string */
    private readonly string $sizeFormat;

    /** @var non-empty-string */
    private readonly string $imageFormat;

    /** @var non-empty-string */
    private readonly string $imageId;

    public function __construct(
        string $checksum,
        string $strategyName,
        string $sizeFormat,
        string $imageFormat,
        string $imageId,
    ) {
        $this->checksum = self::assertNotEmpty($checksum);
        $this->strategyName = self::assertNotEmpty($strategyName);
        $this->sizeFormat = self::assertNotEmpty($sizeFormat);
        $this->imageFormat = self::assertNotEmpty($imageFormat);
        $this->imageId = self::assertNotEmpty($imageId);
    }

    /**
     * @return non-empty-string
     */
    private static function assertNotEmpty(string $value): string
    {
        if (empty($value)) {
            throw new \InvalidArgumentException();
        }

        return $value;
    }

    /** {@inheritDoc} */
    public function getChecksum(): string
    {
        return $this->checksum;
    }

    /** {@inheritDoc} */
    public function getStrategyName(): string
    {
        return $this->strategyName;
    }

    /** {@inheritDoc} */
    public function getSizeFormat(): string
    {
        return $this->sizeFormat;
    }

    /** {@inheritDoc} */
    public function getImageFormat(): string
    {
        return $this->imageFormat;
    }

    /** {@inheritDoc} */
    public function getImageId(): string
    {
        return $this->imageId;
    }
}
