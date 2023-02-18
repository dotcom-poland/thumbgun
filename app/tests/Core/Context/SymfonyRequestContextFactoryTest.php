<?php

declare(strict_types=1);

namespace Test\App\Core\Context;

use App\Core\Context\SymfonyRequestContextFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class SymfonyRequestContextFactoryTest extends TestCase
{
    private RequestStack $requestStack;
    private SymfonyRequestContextFactory $sut;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->sut = new SymfonyRequestContextFactory($this->requestStack);
    }

    public function testItCreatesContextFromRequest(): void
    {
        $this->requestStack->push(new Request([], [], [
            'checksum' => 'checksum',
            'strategy' => 'strategy',
            'size' => 'size',
            'format' => 'format',
            'id' => 'id',
        ]));

        ($this->sut)();

        $this->expectNotToPerformAssertions();
    }

    public function testItDoesNotAllowMissingChecksum(): void
    {
        $this->requestStack->push(new Request([], [], [
            'checksum' => '',
            'strategy' => 'strategy',
            'size' => 'size',
            'format' => 'format',
            'id' => 'id',
        ]));

        $this->expectException(\InvalidArgumentException::class);

        ($this->sut)();
    }

    public function testItDoesNotAllowMissingStrategy(): void
    {
        $this->requestStack->push(new Request([], [], [
            'checksum' => 'checksum',
            'strategy' => '',
            'size' => 'size',
            'format' => 'format',
            'id' => 'id',
        ]));

        $this->expectException(\InvalidArgumentException::class);

        ($this->sut)();
    }

    public function testItDoesNotAllowMissingSize(): void
    {
        $this->requestStack->push(new Request([], [], [
            'checksum' => 'checksum',
            'strategy' => 'strategy',
            'size' => '',
            'format' => 'format',
            'id' => 'id',
        ]));

        $this->expectException(\InvalidArgumentException::class);

        ($this->sut)();
    }

    public function testItDoesNotAllowMissingFormat(): void
    {
        $this->requestStack->push(new Request([], [], [
            'checksum' => 'checksum',
            'strategy' => 'strategy',
            'size' => 'size',
            'format' => '',
            'id' => 'id',
        ]));

        $this->expectException(\InvalidArgumentException::class);

        ($this->sut)();
    }

    public function testItDoesNotAllowMissingImageId(): void
    {
        $this->requestStack->push(new Request([], [], [
            'checksum' => 'checksum',
            'strategy' => 'strategy',
            'size' => 'size',
            'format' => 'format',
            'id' => '',
        ]));

        $this->expectException(\InvalidArgumentException::class);

        ($this->sut)();
    }

    public function testDoesNotWorkWithoutStackRequest(): void
    {
        $this->expectException(\Exception::class);

        ($this->sut)();
    }
}
