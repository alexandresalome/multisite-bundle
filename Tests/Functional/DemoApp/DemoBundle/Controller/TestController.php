<?php

namespace Alex\MultisiteBundle\Tests\Functional\DemoApp\DemoBundle\Controller;

use Alex\MultisiteBundle\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    /**
     * @Route(name="test_index", path="/")
     * @Template("AlexMultisiteDemoBundle:Test:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route(name="test_locale", paths={
     *   "fr_FR"="/page-fr",
     *   "en_GB"="/page-en",
     *   "de_DE"="/page-de"
     * })
     * @Template("AlexMultisiteDemoBundle:Test:locale.html.twig")
     */
    public function localeAction()
    {
        return array();
    }

    /**
     * @Route(name="test_branding_locale", paths={
     *   "foo"={
     *     "fr_FR"="/test-foo-fr",
     *     "en_GB"="/test-foo-en",
     *   },
     *   "bar"={
     *     "fr_FR"="/test-bar-fr",
     *     "de_DE"="/test-bar-de",
     *   }
     * })
     * @Template
     */
    public function brandingLocaleAction()
    {
        return array();
    }
}
