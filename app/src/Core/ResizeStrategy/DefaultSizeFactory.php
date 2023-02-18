<?php

declare(strict_types=1);

namespace App\Core\ResizeStrategy;

use App\Core\RequestContextInterface;
use App\Core\ResizeStrategy\Exception\SizeException;

/** {@inheritDoc} */
final class DefaultSizeFactory implements SizeFactoryInterface
{
    /** {@inheritDoc} */
    public function __invoke(RequestContextInterface $context): SizeInterface
    {
        if (\preg_match('/^\d+x\d+$/', $context->getSizeFormat())) {
            return SizeRectangle::fromString($context->getSizeFormat());
        }

        throw new SizeException();
    }
}
