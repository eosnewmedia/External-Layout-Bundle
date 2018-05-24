<?php

namespace Enm\Bundle\ExternalLayoutBundle\DependencyInjection;

use Enm\Bundle\ExternalLayoutBundle\Command\CreateLayoutsCommand;
use Enm\ExternalLayout\Finisher\FinisherChain;
use Enm\ExternalLayout\Finisher\WorkingTagFinisher;
use Enm\ExternalLayout\LayoutCreator;
use Enm\ExternalLayout\Loader\GuzzleLoader;
use Enm\ExternalLayout\Loader\LoaderInterface;
use Enm\ExternalLayout\Manipulator\ManipulatorChain;
use Enm\ExternalLayout\Manipulator\ManipulatorInterface;
use Enm\ExternalLayout\Manipulator\TwigManipulator;
use Enm\ExternalLayout\Manipulator\UrlManipulator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class EnmExternalLayoutExtension extends ConfigurableExtension
{
    /**
     * Configures the passed container according to the merged configuration.
     *
     * @param array $mergedConfig
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        if ($mergedConfig['useGuzzle']) {
            $container->autowire(GuzzleLoader::class)->setPublic(false);
            $container->setAlias(LoaderInterface::class, GuzzleLoader::class)->setPublic(false);
        }

        $container->autowire(ManipulatorChain::class)->setPublic(false);
        $container->setAlias(ManipulatorInterface::class, ManipulatorChain::class)->setPublic(false);

        $container->autowire(FinisherChain::class)->setPublic(false);
        $container->setAlias(ManipulatorInterface::class, FinisherChain::class)->setPublic(false);

        $container->autowire(TwigManipulator::class)->setPublic(false)->addTag('external_layout.manipulator');
        $container->autowire(UrlManipulator::class)->setPublic(false)->addTag('external_layout.manipulator');
        $container->autowire(WorkingTagFinisher::class)->setPublic(false)->addTag('external_layout.finisher');

        $container->autowire(LayoutCreator::class)->setPublic(false);

        $container->autowire(CreateLayoutsCommand::class)
            ->addArgument($mergedConfig['layouts'])
            ->setPublic(true)->addTag('console.command');
    }
}
