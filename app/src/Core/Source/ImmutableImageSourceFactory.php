<?php

declare(strict_types=1);

namespace App\Core\Source;

final class ImmutableImageSourceFactory implements ImageSourceFactoryInterface
{
    public function __construct(
        private readonly ImageSourceInterface $source,
    ) {}

    public function __invoke(): ImageSourceInterface
    {
        return $this->source;
    }
}
