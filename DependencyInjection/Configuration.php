<?php

namespace Alex\MultisiteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration for multisite extension.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('alex_multisite');

        $rootNode
            ->children()
                ->booleanNode('sort_routes')->defaultTrue()->end()
                ->scalarNode('default_branding')->isRequired()->end()
                ->scalarNode('default_locale')->isRequired()->end()
                ->arrayNode('default_config')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('brandings')
                    ->prototype('variable')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
