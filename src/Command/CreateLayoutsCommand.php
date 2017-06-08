<?php
namespace Enm\Bundle\ExternalLayoutBundle\Command;

use Enm\Bundle\ExternalLayoutBundle\Service\LayoutService;
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
     * @var LayoutService
     */
    private $layoutService;
    
    /**
     * @var array
     */
    private $layouts;
    
    /**
     * CreateLayoutCommand constructor.
     *
     * @param LayoutService $layoutService
     * @param array $layouts
     *
     * @throws \Exception
     */
    public function __construct(LayoutService $layoutService, array $layouts)
    {
        $this->layoutService = $layoutService;
        $this->layouts       = $layouts;
        parent::__construct('enm:external-layout:create');
    }
    
    /**
     * Configures the current command.
     */
    protected function configure()
    {
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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io       = new SymfonyStyle($input, $output);
        $progress = $io->createProgressBar(count($this->layouts));
        $progress->start();
        
        $created = [];
        $ignored = [];
        foreach ($this->layouts as $layout => $config) {
            if ($this->shouldCreate($input, $layout)) {
                $this->layoutService->createLayout($layout, $config);
                $created[] = $layout;
            } else {
                $ignored[] = $layout;
            }
            $progress->advance();
        }
        
        $progress->finish();
        
        if (count($created) > 0) {
            $io->newLine(2);
            $io->success('Created '.count($created).' layouts:');
            $io->listing($created);
        }
        
        if (count($ignored) > 0) {
            $io->newLine(2);
            $io->note('Ignored '.count($ignored).' layouts:');
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
    private function shouldCreate(InputInterface $input, $layout)
    {
        /** @var array $layoutOption */
        $layoutOption = $input->getOption('layout');
        $isInArray    = in_array($layout, $layoutOption, true);
        
        return !(count($layoutOption) > 0 && !$isInArray);
    }
}
