<?php
namespace Enm\Bundle\ExternalLayoutBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class BlockBuilderPass extends AbstractLayoutCompilerPass
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('enm.external_layout.block_builder_registry')) {
            return;
        }
        
        $definition     = $container->getDefinition('enm.external_layout.block_builder_registry');
        $taggedServices = $container->findTaggedServiceIds('external_layout.block_builder');
        
        $this->addMethodCallForServices(
          $definition,
          $taggedServices,
          'addBlockBuilder'
        );
    }
}
