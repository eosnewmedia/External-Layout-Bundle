<?php
namespace Enm\Bundle\ExternalLayoutBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractLayoutCompilerPass implements CompilerPassInterface
{
    /**
     * @param Definition $definition
     * @param array $taggedServices
     * @param string $method
     *
     * @throws \Exception
     * @return $this
     */
    public function addMethodCallForServices(Definition $definition, array $taggedServices, $method)
    {
        /**
         * @var string $id
         * @var array $tags
         */
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                  $method,
                  [
                    $attributes['layout'],
                    new Reference($id),
                  ]
                );
            }
        }
        
        return $this;
    }
}
