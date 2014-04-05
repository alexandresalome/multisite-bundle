<?php

namespace Alex\MultisiteBundle\Router\Loader;

use Alex\MultisiteBundle\Annotation\Route as RouteAnnotation;
use Alex\MultisiteBundle\Branding\SiteContext;
use Alex\MultisiteBundle\Router\MultisiteRouter;
use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader as BaseAnnotatedRouteControllerLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Extends the annotation loader class to load multisite annotations.
 */
class AnnotatedRouteControllerLoader extends BaseAnnotatedRouteControllerLoader
{
    /**
     * @var SiteContext
     */
    protected $siteContext;

    /**
     * Injects the site context into the annotation loader.
     *
     * @return AnnotatedRouteControllerLoader
     */
    public function setSiteContext(SiteContext $siteContext)
    {
        $this->siteContext = $siteContext;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function addRoute(RouteCollection $collection, $annot, $globals, \ReflectionClass $class, \ReflectionMethod $method)
    {
        // If the annotation is not the Multisite's one, call parent method
        if (!$annot instanceof RouteAnnotation) {
            return parent::addRoute($collection, $annot, $globals, $class, $method);
        }

        // mono-route
        if (null === $annot->getPaths()) {
            return parent::addRoute($collection, $annot, $globals, $class, $method);
        }


        return $this->addMultisiteRoute($collection, $annot, $globals, $class, $method);
    }

    /**
     * Specific method to add a multisite route to the collection.
     *
     * Paths option should not be null.
     */
    protected function addMultisiteRoute(RouteCollection $collection, $annot, $globals, \ReflectionClass $class, \ReflectionMethod $method)
    {
        $paths = $this->siteContext->normalizePaths($annot->getPaths());

        foreach ($paths as $branding => $locales) {
            foreach ($locales as $locale => $path) {

                // this block of code is copied from Symfony\Component\Routing\Loader\AnnotationFileLoader
                $name = $annot->getName();
                if (null === $name) {
                    $name = $this->getDefaultRouteName($class, $method);
                }
                $name = MultisiteRouter::ROUTE_PREFIX.'_'.$branding.'_'.$locale.'__'.$name;

                $defaults = array_replace($globals['defaults'], $annot->getDefaults());
                foreach ($method->getParameters() as $param) {
                    if (!isset($defaults[$param->getName()]) && $param->isOptional()) {
                        $defaults[$param->getName()] = $param->getDefaultValue();
                    }
                }

                // +2 lines
                $defaults['_branding'] = $branding;
                $defaults['_locale']   = $locale;

                $requirements = array_replace($globals['requirements'], $annot->getRequirements());
                $options = array_replace($globals['options'], $annot->getOptions());
                $schemes = array_replace($globals['schemes'], $annot->getSchemes());
                $methods = array_replace($globals['methods'], $annot->getMethods());

                $host = $annot->getHost();
                if (null === $host) {
                    $host = $globals['host'];
                }

                // +3 lines
                if (!$host) {
                    $host = $this->siteContext->getBranding($branding)->getHost($locale);
                }

                $condition = $annot->getCondition();
                if (null === $condition) {
                    $condition = $globals['condition'];
                }

                $route = new Route($globals['path'].$path, $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
                $this->configureRoute($route, $class, $method, $annot);

                $collection->add($name, $route);
            }
        }

        // cache will refresh when file is modified
        $collection->addResource(new FileResource($class->getFileName()));

        return $collection;
    }
}
