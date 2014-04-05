<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Alex\MultisiteBundle\Tests\Functional\Fixtures\AppKernel;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\HttpKernel\Client;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{
    static public function createClient()
    {
        $app = new AppKernel('dev', true);

        return new Client($app);
    }
}
