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
        $container->setParameter('alex_multisite.sort_routes', $config['sort_routes']);

        $loader->load('site_context.xml');
        $loader->load('framework_extra.xml');
        $loader->load('twig.xml');

        $this->addBrandingDefinition($container, $config['brandings']);
    }

    /**
     * Adds configured brandings to the site context service.
     *
     * @param ContainerBuilder $container
     * @param array            $options
     */
    private function addBrandingDefinition(ContainerBuilder $container, array $options)
    {
        $brandings = array();

        if (isset($options['_defaults'])) {
            $globalOptions = $options['_defaults'];
            unset($options['_defaults']);
        } else {
            $globalOptions = array();
        }

        foreach ($options as $name => $localeOptions) {
            if (isset($localeOptions['_defaults'])) {
                $brandingOptions = $localeOptions['_defaults'];
                unset($localeOptions['_defaults']);
            } else {
                $brandingOptions = array();
            }

            $arg = array();
            foreach ($localeOptions as $locale => $options) {
                $arg[$locale] = array_merge($globalOptions, $brandingOptions, $options);
            }

            $brandings[] = new Definition(
                'Alex\MultisiteBundle\Branding\Branding',
                array($name, $arg)
            );
        }

        $container->getDefinition('site_context')->replaceArgument(0, $brandings);
    }
}
