<?php

declare(strict_types=1);

namespace App\DevTools;

use App\Core\Security\ChecksumBuilderInterface;
use App\Core\Security\VaultInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Routing\RouterInterface;

final class UrlGenerateCommand extends Command
{
    private readonly RouterInterface $router;
    private readonly ChecksumBuilderInterface $checksumBuilder;
    private readonly VaultInterface $vault;

    public function __construct(
        RouterInterface $router,
        ChecksumBuilderInterface $checksumBuilder,
        VaultInterface $vault
    ) {
        parent::__construct();
        $this->router = $router;
        $this->checksumBuilder = $checksumBuilder;
        $this->vault = $vault;
    }

    protected function configure(): void
    {
        $this->setName('dev:url-generate')
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

        try {
            $key = $this->resolveKey($input, $output);
        } catch (\RuntimeException) {
            $output->writeln('<error>No secret keys configured</error>');

            return self::INVALID;
        }

        $checksum = ($this->checksumBuilder)(
            $strategy,
            $size,
            $imageId,
            $key,
        );

        $url = $this->router->generate('thumbnail_serve', [
            'checksum' => $checksum,
            'strategy' => $strategy,
            'size' => $size,
            'id' => $imageId,
            'format' => $format,
        ]);

        $output->writeln($url);

        return 0;
    }

    /** @throws \RuntimeException */
    private function resolveKey(InputInterface $input, OutputInterface $output): string
    {
        $secretKeys = \iterator_to_array($this->vault);

        if (empty($secretKeys)) {
            throw new \RuntimeException();
        }

        if (1 === \count($secretKeys)) {
            return $secretKeys[0];
        }

        $question = new ChoiceQuestion('Multiple keys available. Select the key to use:', $secretKeys);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        /** @var string $key */
        $key = $helper->ask($input, $output, $question);

        return $key;
    }
}
