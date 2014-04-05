<?php

namespace Alex\MultisiteBundle\Twig;

use Alex\MultisiteBundle\Branding\SiteContext;

/**
 * Wraps a Twig Loader and extends template syntax
 */
class MultisiteLoader implements \Twig_LoaderInterface
{
    /**
     * @var Twig_LoaderInterface
     */
    private $loader;

    /**
     * @var SiteContext
     */
    private $siteContext;

    /**
     * Constructs the loader.
     *
     * @param Twig_LoaderInterface $loader a twig loader
     */
    public function __construct(\Twig_LoaderInterface $loader, SiteContext $siteContext)
    {
        $this->loader      = $loader;
        $this->siteContext = $siteContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource($name)
    {
        $templates = $this->getTemplates($name);

        foreach ($templates as $template) {
            try {
                return $this->loader->getSource($template);
            } catch (\Twig_Error $e) {
            }
        }

        throw new \RuntimeException(sprintf("Template \"%s\" not found. Tried the following:\n%s", $name, implode("\n", $templates)));
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKey($name)
    {
        $templates = $this->getTemplates($name);

        foreach ($templates as $template) {
            try {
                return $this->loader->getCacheKey($template);
            } catch (\Twig_Error $e) {
            }
        }

        throw new \RuntimeException(sprintf("Template \"%s\" not found. Tried the following:\n%s", $name, implode("\n", $templates)));
    }

    /**
     * {@inheritdtemplateoc}
     */
    public function isFresh($name, $time)
    {
        $templates = $this->getTemplates($name);

        foreach ($templates as $template) {
            try {
                return $this->loader->isFresh($template, $time);
            } catch (\Twig_Error $e) {
            }
        }

        throw new \RuntimeException(sprintf("Template \"%s\" not found. Tried the following:\n%s", $name, implode("\n", $templates)));
    }

    /**
     * {@inheritdoc}
     */
    private function getTemplates($name)
    {
        $posA = strrpos($name, ':');
        $posB = strrpos($name, '/');
        $posC = strrpos($name, '/');

        $b = $this->siteContext->getCurrentBrandingName();
        $l = $this->siteContext->getCurrentLocale();

        if ($posA === false && $posB === false && $posC === false) {
            $prefix = '';
            $suffix = '/'.$name;
        } else {
            $pos = max($posA, $posB, $posC);
            $prefix = substr($name, 0, $pos + 1);
            $suffix = '/'.substr($name, $pos + 1);
        }

        return array(
            $prefix.'_'.$b.'_'.$l.''.$suffix,
            $prefix.'_'.$b.'_'.$suffix,
            $prefix.'__'.$l.''.$suffix,
            $name
        );
    }
}
