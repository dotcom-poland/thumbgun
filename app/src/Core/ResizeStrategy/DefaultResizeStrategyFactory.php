<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\ResizeStrategy\Exception\ResizeStrategyMissingException;

final class DefaultResizeStrategyFactory implements ResizeStrategyFactoryInterface
{
    public function __construct(
        private readonly ResizeStrategyFixed $fixed,
    ) {}

    public function __invoke(string $name): ResizeStrategyInterface
    {
        return match ($name) {
            'fixed' => $this->fixed,
            default => throw new ResizeStrategyMissingException(),
        };
    }
}
