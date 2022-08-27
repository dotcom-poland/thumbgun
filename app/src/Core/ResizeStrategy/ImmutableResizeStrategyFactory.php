<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\ResizeStrategy\Exception\ResizeStrategyException;

final class ImmutableResizeStrategyFactory implements ResizeStrategyFactoryInterface
{
    /**
     * @param array<string, ResizeStrategyInterface> $strategies
     */
    public function __construct(
        private readonly array $strategies
    ) {}

    /** {@inheritDoc} */
    public function __invoke(string $name): ResizeStrategyInterface
    {
        if (false === isset($this->strategies[$name])) {
            throw new ResizeStrategyException();
        }

        return $this->strategies[$name];
    }
}
