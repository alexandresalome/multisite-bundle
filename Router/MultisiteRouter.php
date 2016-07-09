<?php

namespace Alex\MultisiteBundle\Router;

use Alex\MultisiteBundle\Branding\SiteContext;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouteCollection;

/**
 * Extends Symfony router to add multisite capabilities.
 */
class MultisiteRouter extends Router
{
    const ROUTE_PREFIX = '_i18n_';

    /**
     * @var SiteContext
     */
    private $siteContext;

    /**
     * @var boolean
     */
    private $sortRoutes = true;

    /**
     * Defines if router should sort routes by local and domain, to optimize
     * matching performance.
     */
    public function setSortRoutes($value = true)
    {
        $this->sortRoutes = $value;
    }

    /**
     * Changes the site context.
     *
     * @param SiteContext $siteContext
     *
     * @return MultisiteRouter
     */
    public function setSiteContext(SiteContext $siteContext)
    {
        $this->siteContext = $siteContext;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function matchRequest(Request $request)
    {
        $match = parent::matchRequest($request);
        $this->setMatchContext($match);

        return $match;
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        $match = parent::match($pathinfo);
        $this->setMatchContext($match);

        return $match;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        if (isset($parameters['_branding'])) {
            $branding = $parameters['_branding'];
            unset($parameters['_branding']);
        } else {
            $branding = $this->getSiteContext()->getCurrentBrandingName();
        }

        if (isset($parameters['_locale'])) {
            $locale = $parameters['_locale'];
            unset($parameters['_locale']);
        } else {
            $locale = $this->siteContext->getCurrentLocale();
        }

        if (null !== $branding && null !== $locale) {
            $multisiteName = self::ROUTE_PREFIX.'_'.$branding.'_'.$locale.'__'.$name;
            try {
                return parent::generate($multisiteName, $parameters, $referenceType);
            } catch (RouteNotFoundException $e) {
                // fallback to default behavior
            }
        }

        return parent::generate($name, $parameters, $referenceType);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        $collection    = parent::getRouteCollection();
        $routes        = $collection->all();
        $newCollection = new RouteCollection();

        $routes = $this->sortRoutes($routes);
        foreach ($routes as $name => $route) {
            $newCollection->add($name, $route);
        }

        return $newCollection;
    }

    /**
     * Sort routes by domain.
     *
     * @param Route[] $routes
     *
     * @return Route[]
     */
    protected function sortRoutes(array $routes)
    {
        if (!$this->sortRoutes) {
            return $routes;
        }

        // group by host is a good-enough strategy for most of the cases
        $hosts = array();
        foreach ($routes as $name => $route) {
            $branding = $route->getDefault('_branding');
            $locale   = $route->getDefault('_locale');
            $hosts[$branding.'__'.$locale][$name] = $route;
        }

        return call_user_func_array('array_merge', $hosts);
    }

    /**
     * @return SiteContext
     *
     * @throws RuntimeException If no site context is available
     */
    private function getSiteContext()
    {
        if (null === $this->siteContext) {
            throw new \RuntimeException('No site context injected in router.');
        }

        return $this->siteContext;
    }

    /**
     * Changes router context and site context according to matched route.
     *
     * @param array $match
     */
    private function setMatchContext(array $match)
    {
        if (isset($match['_branding'])) {
            $this->context->setParameter('_branding', $match['_branding']);

            $this->siteContext->setCurrentBranding($this->siteContext->getBranding($match['_branding']));
        }

        if (isset($match['_locale'])) {
            $this->context->setParameter('_locale', $match['_locale']);

            $this->siteContext->setCurrentLocale($match['_locale']);
        }
    }
}
