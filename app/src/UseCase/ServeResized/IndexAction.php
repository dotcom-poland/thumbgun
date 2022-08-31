<?php

declare(strict_types=1);

namespace App\UseCase\ServeResized;

use App\Core\Image\Exception\ImageException;
use App\Core\Image\SupportedImagesInterface;
use App\Core\Processor\ThumbnailProcessorInterface;
use App\Core\ResizeStrategy\Exception\ResizeStrategyException;
use App\Core\ResizeStrategy\Exception\SizeException;
use App\Core\ResizeStrategy\ResizeStrategyFactoryInterface;
use App\Core\ResizeStrategy\SizeFactoryInterface;
use App\Core\Source\Exception\ImageNotFoundException;
use App\Core\Source\ImageSourceFactoryInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class IndexAction
{
    public function __construct(
        private readonly SupportedImagesInterface       $supportedFormats,
        private readonly SizeFactoryInterface           $sizeFactory,
        private readonly ResizeStrategyFactoryInterface $resizeStrategyFactory,
        private readonly ImageSourceFactoryInterface    $sourceFactory,
        private readonly ThumbnailProcessorInterface    $thumbnailProcessor,
        private readonly LoggerInterface                $logger,
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(Request $request): Response
    {
        /** @var non-empty-string $strategyName */
        $strategyName = $request->attributes->get('strategy');

        /** @var non-empty-string $sizeFormat */
        $sizeFormat = $request->attributes->get('size');

        /** @var non-empty-string $imageFormat */
        $imageFormat = $request->attributes->get('format');

        /** @var non-empty-string $imageId */
        $imageId = $request->attributes->get('id');

        if (false === $this->supportedFormats->isSupported($imageFormat)) {
            return new Response('Unsupported format', Response::HTTP_BAD_REQUEST);
        }

        try {
            $strategy = ($this->resizeStrategyFactory)($strategyName);
            $size = ($this->sizeFactory)($sizeFormat);
        } catch (ResizeStrategyException|SizeException) {
            return new Response('Unsupported size format', Response::HTTP_BAD_REQUEST);
        }

        $source = ($this->sourceFactory)();

        try {
            $image = ($source)($imageId, $imageFormat);
        } catch (ImageNotFoundException) {
            return new Response('No such image', Response::HTTP_NOT_FOUND);
        } catch (ImageException) {
            return new Response('Invalid image requested', Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            $this->logger->error('Failed serving the thumbnail from the source', \array_merge([
                $request->attributes->all(),
                ['exception' => $exception],
            ]));

            return new Response('Failed serving the request from the store', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $thumbnail = ($this->thumbnailProcessor)($image, $strategy, $size);
        } catch (Exception $exception) {
            $this->logger->error('Failed serving the thumbnail', \array_merge([
                $request->attributes->all(),
                ['exception' => $exception],
            ]));

            return new Response('Failed serving the request', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response = new StreamedResponse();
        $response->headers->set('Content-Type', \sprintf('image/%s', $image->getRequestedFormat()));
        $response->headers->set('Content-Disposition', 'inline');
        $response->setCallback(static function () use ($thumbnail): void {
            $thumbnail->fpassthru();
        });

        return $response;
    }
}
