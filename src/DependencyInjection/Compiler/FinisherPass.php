<?php
declare(strict_types=1);

namespace Enm\Bundle\ExternalLayoutBundle\DependencyInjection\Compiler;

use Enm\ExternalLayout\Finisher\FinisherChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class FinisherPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(FinisherChain::class)) {
            return;
        }

        foreach ($container->findTaggedServiceIds('external_layout.finisher') as $service => $tags) {
            $container->getDefinition(FinisherChain::class)->addMethodCall('register', [new Reference($service)]);
        }
    }
}
