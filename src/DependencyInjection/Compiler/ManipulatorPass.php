<?php
declare(strict_types=1);

namespace Enm\Bundle\ExternalLayoutBundle\DependencyInjection\Compiler;

use Enm\ExternalLayout\Manipulator\ManipulatorChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ManipulatorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(ManipulatorChain::class)) {
            return;
        }

        foreach ($container->findTaggedServiceIds('external_layout.manipulator') as $service => $tags) {
            $container->getDefinition(ManipulatorChain::class)->addMethodCall('register', [new Reference($service)]);
        }
    }
}
