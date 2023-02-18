<?php

declare(strict_types=1);

namespace Test\App\UseCase\ServeResized;

use App\Core\Context\SymfonyRequestContextFactory;
use App\Core\Image\Exception\ImageException;
use App\Core\Image\ImmutableSupportedImages;
use App\Core\Processor\DefaultThumbnailProcessor;
use App\Core\ResizeStrategy\DefaultSizeFactory;
use App\Core\ResizeStrategy\ImmutableResizeStrategyFactory;
use App\Core\Source\Exception\ImageNotFoundException;
use App\Core\Source\ImmutableImageSourceFactory;
use App\UseCase\ServeResized\IndexAction;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Test\App\Core\ResizeStrategy\DummyResizeStrategy;
use Test\App\Core\Security\TestChecksumValidator;
use Test\App\Core\Source\TestImageSource;

final class IndexActionTest extends TestCase
{
    private readonly TestImageSource $source;
    private readonly DummyResizeStrategy $resizeStrategy;

    protected function setUp(): void
    {
        $this->source = new TestImageSource();
        $this->resizeStrategy = new DummyResizeStrategy('test');
    }

    public function testItServesResizedImage(): Response
    {
        $response = $this->dispatch();

        $this->expectNotToPerformAssertions();

        return $response;
    }

    /** @depends testItServesResizedImage */
    public function testItServesProperContentType(Response $response): void
    {
        self::assertSame('image/webp', $response->headers->get('Content-Type'));
    }

    /** @depends testItServesResizedImage */
    public function testItServesInlineContent(Response $response): void
    {
        self::assertSame('inline', $response->headers->get('Content-Disposition'));
    }

    /** @depends testItServesResizedImage */
    public function testItServesThumbnailContent(Response $response): void
    {
        self::assertSame('test', $response->getContent());
    }

    public function testHandlesInvalidChecksum(): void
    {
        $response = $this->dispatch([
            'checksum' => 'invalid',
        ]);

        self::assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testHandlesUnsupportedFormat(): void
    {
        $response = $this->dispatch([
            'format' => 'gif',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testHandlesUnsupportedResizeStrategy(): void
    {
        $response = $this->dispatch([
            'strategy' => 'fake',
            'format' => 'jpeg',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testHandlesUnsupportedSize(): void
    {
        $response = $this->dispatch([
            'strategy' => 'dummy',
            'size' => '0x0',
            'format' => 'jpeg',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testHandlesImageNotFound(): void
    {
        $this->source->setThrownException(new ImageNotFoundException());

        $response = $this->dispatch();

        self::assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testHandlesInvalidImageException(): void
    {
        $this->source->setThrownException(new ImageException());

        $response = $this->dispatch();

        self::assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testHandlesAnySourceException(): void
    {
        $this->source->setThrownException(new Exception());

        $response = $this->dispatch();

        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testHandlesAnyProcessorException(): void
    {
        $this->resizeStrategy->setThrownException(new Exception());

        $response = $this->dispatch();

        self::assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    private function dispatch(array $attributes = []): Response
    {
        $attributes = \array_merge([
            'strategy' => 'dummy',
            'size' => '50x50',
            'format' => 'webp',
            'id' => 'a/b/c/123.jpeg',
        ], $attributes);

        $checksum = \vsprintf('%s:%s:%s:%s', [
            $attributes['strategy'],
            $attributes['size'],
            $attributes['id'],
            $attributes['format'],
        ]);

        $request = new Request([], [], \array_merge(
            ['checksum' => $checksum],
            $attributes,
        ));

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $action = new IndexAction(
            new SymfonyRequestContextFactory($requestStack),
            new TestChecksumValidator(),
            new ImmutableSupportedImages(['webp']),
            new DefaultSizeFactory(),
            new ImmutableResizeStrategyFactory(['dummy' => $this->resizeStrategy]),
            new ImmutableImageSourceFactory($this->source),
            new DefaultThumbnailProcessor(),
            new NullLogger(),
        );

        return $action($request);
    }
}
