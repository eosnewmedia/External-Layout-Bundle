<?php

namespace Enm\Bundle\ExternalLayoutBundle\Command;

use Enm\ExternalLayout\LayoutCreator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class CreateLayoutsCommand extends Command
{
    /**
     * @var array
     */
    private $layouts;
    /**
     * @var LayoutCreator
     */
    private $layoutCreator;

    /**
     * @param array $layouts
     * @param LayoutCreator $layoutCreator
     * @throws \Exception
     */
    public function __construct(array $layouts, LayoutCreator $layoutCreator)
    {
        parent::__construct('enm:external-layout:create');
        $this->layouts = $layouts;
        $this->layoutCreator = $layoutCreator;

        $this->addOption(
            'layout',
            'l',
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            'specify which layouts from config to (re)create'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $progress = $io->createProgressBar(\count($this->layouts));
        $progress->start();

        $created = [];
        $ignored = [];
        foreach ($this->layouts as $layout => $config) {
            if ($this->shouldCreate($input, $layout)) {
                $this->layoutCreator->createFromConfig($config);
                $created[] = $layout;
            } else {
                $ignored[] = $layout;
            }
            /** @noinspection DisconnectedForeachInstructionInspection */
            $progress->advance();
        }

        $progress->finish();

        if (\count($created) > 0) {
            $io->newLine(2);
            $io->success('Created ' . \count($created) . ' layouts:');
            $io->listing($created);
        }

        if (\count($ignored) > 0) {
            $io->newLine(2);
            $io->note('Ignored ' . \count($ignored) . ' layouts:');
            $io->listing($ignored);
        }
    }

    /**
     * @param InputInterface $input
     * @param string $layout
     *
     * @return bool
     * @throws \Exception
     */
    private function shouldCreate(InputInterface $input, $layout): bool
    {
        /** @var array $layoutOption */
        $layoutOption = $input->getOption('layout');
        $isInArray = \in_array($layout, $layoutOption, true);

        return !(\count($layoutOption) > 0 && !$isInArray);
    }
}
