<?php

namespace Alex\MultisiteBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler pass to inject multisite template loader.
 */
class TwigLoaderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $alias = (string) $container->getAlias('twig.loader');
        $container->setAlias('twig.loader', 'twig.loader.multisite');
        $container->setAlias('twig.loader.previous', $alias);
    }
}
