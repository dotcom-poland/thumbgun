<?php

declare(strict_types=1);

namespace App\Core\Context;

use App\Core\RequestContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class SymfonyRequestContextFactory implements RequestContextFactoryInterface
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function __invoke(): RequestContextInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new \BadMethodCallException('Stack has no request');
        }

        return new ImmutableRequestContext(
            (string) $request->attributes->get('checksum'),
            (string) $request->attributes->get('strategy'),
            (string) $request->attributes->get('size'),
            (string) $request->attributes->get('format'),
            (string) $request->attributes->get('id'),
        );
    }
}
