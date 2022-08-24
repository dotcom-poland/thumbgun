<?php

declare(strict_types=1);

namespace App\Core\Source;

final class ImmutableSourceFactory implements SourceFactoryInterface
{
    public function __construct(
        private readonly SourceInterface $source,
    ) {}

    public function __invoke(): SourceInterface
    {
        return $this->source;
    }
}
