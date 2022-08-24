<?php

declare(strict_types=1);

namespace App\UseCase\ServeResized;

use App\Core\Image\Exception\ImageException;
use App\Core\Processor\ThumbnailProcessorInterface;
use App\Core\ResizeStrategy\Exception\ResizeStrategyMissingException;
use App\Core\ResizeStrategy\Exception\SizeException;
use App\Core\ResizeStrategy\ResizeStrategyFactoryInterface;
use App\Core\ResizeStrategy\SizeFactoryInterface;
use App\Core\Security\ChecksumValidatorInterface;
use App\Core\Source\Exception\ImageNotFoundException;
use App\Core\Source\Exception\SourceException;
use App\Core\Source\SourceFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class IndexAction
{
    /**
     * @param string[] $supportedFormats
     */
    public function __construct(
        private readonly array $supportedFormats,
        private readonly ChecksumValidatorInterface $checksumValidator,
        private readonly SizeFactoryInterface $sizeFactory,
        private readonly ResizeStrategyFactoryInterface $resizeStrategyFactory,
        private readonly SourceFactoryInterface $sourceFactory,
        private readonly ThumbnailProcessorInterface $thumbnailProcessor,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        /** @var non-empty-string $strategyName */
        $strategyName = $request->attributes->get('strategy');

        /** @var non-empty-string $sizeFormat */
        $sizeFormat = $request->attributes->get('size');

        /** @var non-empty-string $imageGroup */
        $imageGroup = $request->attributes->get('group');

        /** @var non-empty-string $imageId */
        $imageId = $request->attributes->get('id');

        /** @var non-empty-string $imageFormat */
        $imageFormat = $request->attributes->get('format');

        /** @var non-empty-string $checksum */
        $checksum = $request->attributes->get('checksum');

        if (false === ($this->checksumValidator)($strategyName, $sizeFormat, $imageGroup, $imageId, $checksum)) {
            return new Response('Invalid checksum', Response::HTTP_FORBIDDEN);
        }

        if (false === \in_array($imageFormat, $this->supportedFormats, true)) {
            return new Response('Unsupported format', Response::HTTP_BAD_REQUEST);
        }

        try {
            $strategy = ($this->resizeStrategyFactory)($strategyName);
        } catch (ResizeStrategyMissingException) {
            return new Response('Unsupported strategy', Response::HTTP_BAD_REQUEST);
        }

        try {
            $size = ($this->sizeFactory)($sizeFormat);
        } catch (SizeException) {
            return new Response('Unsupported size format', Response::HTTP_BAD_REQUEST);
        }

        $source = ($this->sourceFactory)();

        try {
            $image = ($source)($imageGroup, $imageId, $imageFormat);
        } catch (ImageNotFoundException) {
            return new Response('No such image', Response::HTTP_NOT_FOUND);
        } catch (ImageException) {
            return new Response('Image request error', Response::HTTP_BAD_REQUEST);
        } catch (SourceException $exception) {
            $this->logger->error('Failed serving the thumbnail from the source', [
                'strategy' => $strategy,
                'size' => $sizeFormat,
                'group' => $imageGroup,
                'imageId' => $imageId,
                'format' => $imageFormat,
                'exception' => $exception,
            ]);

            return new Response('Failed serving the request from the provider', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $thumbnail = ($this->thumbnailProcessor)($image, $strategy, $size);
        } catch (SizeException) {
            return new Response('Invalid size', Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            $this->logger->error('Failed serving the thumbnail', [
                'strategy' => $strategy,
                'size' => $sizeFormat,
                'group' => $imageGroup,
                'imageId' => $imageId,
                'format' => $imageFormat,
                'exception' => $exception,
            ]);

            return new Response('Failed serving the request', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new BinaryFileResponse(
            file: $thumbnail,
            contentDisposition: ResponseHeaderBag::DISPOSITION_INLINE,
        );
    }
}
