<?php

namespace Alex\MultisiteBundle\DependencyInjection;

use Alex\MultisiteBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Container extension to load multisite services.
 */
class AlexMultisiteExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('alex_multisite.default_branding', $config['default_branding']);
        $container->setParameter('alex_multisite.default_locale', $config['default_locale']);

        $loader->load('site_context.xml');
        $loader->load('framework_extra.xml');
        $loader->load('routing.xml');
        $loader->load('twig.xml');

        $this->addBrandingDefinition($container, $config['brandings']);
    }

    /**
     * Adds configured brandings to the site context service.
     *
     * @param ContainerBuilder $container
     * @param array            $brandings
     */
    private function addBrandingDefinition(ContainerBuilder $container, array $brandings)
    {
        $args = array();
        foreach ($brandings as $name => $locales) {
            $args[] = new Definition(
                'Alex\MultisiteBundle\Branding\Branding',
                array($name, $locales)
            );
        }

        $container->getDefinition('site_context')->replaceArgument(0, $args);
    }
}
