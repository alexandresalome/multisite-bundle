<?php

namespace Alex\MultisiteBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to inject site context in extended services.
 */
class InjectSiteContextPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('sensio_framework_extra.routing.loader.annot_class')->addMethodCall('setSiteContext', array(new Reference('site_context')));
        $container->getDefinition('router.default')->setClass('Alex\MultisiteBundle\Router\MultisiteRouter');
        $container->getDefinition('router.default')->addMethodCall('setSiteContext', array(new Reference('site_context')));
        $container->getDefinition('router.default')->addMethodCall('setSortRoutes', array("%alex_multisite.sort_routes%"));
    }
}
