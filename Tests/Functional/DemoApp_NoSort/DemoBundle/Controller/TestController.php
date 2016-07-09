<?php

namespace Alex\MultisiteBundle\Tests\Functional\DemoApp_NoSort\DemoBundle\Controller;

use Alex\MultisiteBundle\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{

    /**
     * @Route(name="route_A", paths={
     *   "foo"={
     *     "en_GB"="/foo",
     *   },
     *   "bar"={
     *     "fr_FR"="/bar",
     *   }
     * })
     */
    public function routeAAction()
    {
        return new Response('Route A');
    }

    /**
     * @Route(name="route_B", paths={
     *   "foo"={
     *     "en_GB"="/bar",
     *   },
     *   "bar"={
     *     "fr_FR"="/baz",
     *   }
     * })
     */
    public function routeBAction()
    {
        return new Response('Route B');
    }
}
