<?php

declare(strict_types=1);

namespace App\DevTools;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

final class UrlGenerateCommand extends Command
{
    private readonly RouterInterface $router;

    public function __construct(
        RouterInterface $router,
    ) {
        parent::__construct();
        $this->router = $router;
    }

    protected function configure(): void
    {
        $this->setName('dev:url')
            ->setDescription('Generates image link with a valid checksum')
            ->addArgument('strategy', InputArgument::REQUIRED)
            ->addArgument('size', InputArgument::REQUIRED)
            ->addArgument('image-id', InputArgument::REQUIRED)
            ->addArgument('output-format', InputArgument::REQUIRED)
            ->addUsage('fixed 100x100 1/2/3.jpg webp');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var non-empty-string $strategy */
        $strategy = $input->getArgument('strategy');

        /** @var non-empty-string $size */
        $size = $input->getArgument('size');

        /** @var non-empty-string $imageId */
        $imageId = $input->getArgument('image-id');

        /** @var non-empty-string $format */
        $format = $input->getArgument('output-format');

        $url = $this->router->generate('thumbnail_serve', [
            'strategy' => $strategy,
            'size' => $size,
            'id' => $imageId,
            'format' => $format,
        ]);

        $output->writeln($url);

        return 0;
    }
}
