<?php
namespace Enm\Bundle\ExternalLayoutBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     * @throws \Exception
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root    = $builder->root('enm_external_layout')->children();
        
        /** @var ArrayNodeDefinition $layouts */
        $layouts = $root->arrayNode('layouts')
                        ->useAttributeAsKey('name')
                        ->prototype('array');
        $layout  = $layouts->children();
        
        $source = $layout->arrayNode('source')->children();
        $source->enumNode('scheme')
               ->defaultValue('http')
               ->values(['http', 'https']);
        $source->scalarNode('host')->isRequired()->cannotBeEmpty();
        $source->scalarNode('path')->defaultValue('/');

        $source->scalarNode('user')->defaultValue('');
        $source->scalarNode('password')->defaultNull();

        $blocks = $layout->arrayNode('blocks')->children();
        $blocks->arrayNode('prepend')
               ->useAttributeAsKey('name')
               ->prototype('scalar')
               ->cannotBeEmpty();
        $blocks->arrayNode('append')
               ->useAttributeAsKey('name')
               ->prototype('scalar')
               ->cannotBeEmpty();
        $blocks->arrayNode('replace')
               ->useAttributeAsKey('name')
               ->prototype('scalar')
               ->cannotBeEmpty();
        
        return $builder;
    }
}
