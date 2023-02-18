<?php

declare(strict_types=1);

namespace App\Core;

/**
 * @psalm-immutable
 */
interface RequestContextInterface
{
    /** @return non-empty-string */
    public function getChecksum(): string;

    /** @return non-empty-string */
    public function getStrategyName(): string;

    /** @return non-empty-string */
    public function getSizeFormat(): string;

    /** @return non-empty-string */
    public function getImageFormat(): string;

    /** @return non-empty-string */
    public function getImageId(): string;
}
