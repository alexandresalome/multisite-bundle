<?php

namespace Alex\MultisiteBundle\Annotation;

use Alex\MultisiteBundle\Branding\SiteContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as BaseRoute;

/**
 * Extends @Route annotation to add a new option: paths.
 *
 * This option allows you to configure a multisite route:
 *
 * @Route(paths={
 *   "fr_FR"="/connexion",
 *   "en_GB"="/login",
 * })
 *
 * @Route(paths={
 *   "foo"={
 *     "fr_FR"="/connexion",
 *     "en_GB"="/login"
 *   },
 *   "bar"={
 *     "fr_FR"="/se-connecter",
 *     "en_GB"="/connect"
 *   }
 * })
 *
 * @Annotation
 */
class Route extends BaseRoute
{
    /**
     * @var array|null
     */
    private $paths;

    /**
     * @return Route
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getPaths()
    {
        return $this->paths;
    }
}
