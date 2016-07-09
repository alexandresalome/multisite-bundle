<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\HttpKernel\Client;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{
    static public function createClient($fixture = 'DemoApp')
    {
        $class = 'Alex\MultisiteBundle\Tests\Functional\\'.$fixture.'\AppKernel';
        $app = new $class('dev', true);

        return new Client($app);
    }
}
