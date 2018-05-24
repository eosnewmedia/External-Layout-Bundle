<?php

namespace Enm\Bundle\ExternalLayoutBundle\DependencyInjection;

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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder();
        $root = $builder->root('enm_external_layout')->children();
        $root->booleanNode('useGuzzle')->defaultFalse();

        $layout = $root->arrayNode('layouts')
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->children();

        $layout->scalarNode('source')->isRequired()->cannotBeEmpty();
        $layout->scalarNode('destination')->isRequired()->cannotBeEmpty();

        $blocks = $layout->arrayNode('blocks')->children();
        $blocks->arrayNode('prepend')
            ->useAttributeAsKey('name')
            ->scalarPrototype()
            ->cannotBeEmpty();
        $blocks->arrayNode('append')
            ->useAttributeAsKey('name')
            ->scalarPrototype()
            ->cannotBeEmpty();
        $blocks->arrayNode('replace')
            ->useAttributeAsKey('name')
            ->scalarPrototype()
            ->cannotBeEmpty();

        return $builder;
    }
}
