<?php

declare(strict_types=1);

namespace Test\App\UseCase\ServeResized;

use App\Core\Image\DefaultSizeFactory;
use App\Core\Image\Exception\ImageException;
use App\Core\Image\ImmutableSupportedImages;
use App\Core\Processor\DefaultThumbnailProcessor;
use App\Core\ResizeStrategy\ImmutableResizeStrategyFactory;
use App\Core\Source\Exception\ImageNotFoundException;
use App\Core\Source\ImmutableImageSourceFactory;
use App\UseCase\ServeResized\IndexAction;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $this->source = new TestImageSource(new \SplFileObject(__DIR__ . '/tiny.jpg'));
        $this->resizeStrategy = new DummyResizeStrategy();
    }

    public function testItServesResizedImage(): void
    {
        $response = $this->dispatch();

        self::assertInstanceOf(BinaryFileResponse::class, $response);
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
        $action = new IndexAction(
            new TestChecksumValidator(),
            new ImmutableSupportedImages(['webp']),
            new DefaultSizeFactory(),
            new ImmutableResizeStrategyFactory(['dummy' => $this->resizeStrategy]),
            new ImmutableImageSourceFactory($this->source),
            new DefaultThumbnailProcessor(),
            new NullLogger(),
        );

        $attributes = \array_merge([
            'strategy' => 'dummy',
            'size' => '50x50',
            'format' => 'webp',
            'id' => '123.jpeg',
        ], $attributes);

        $checksum = \vsprintf('%s:%s:%s', [
            $attributes['strategy'],
            $attributes['size'],
            $attributes['id'],
        ]);

        $request = new Request([], [], \array_merge(
            ['checksum' => $checksum],
            $attributes,
        ));

        return $action($request);
    }
}