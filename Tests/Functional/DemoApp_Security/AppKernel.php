<?php

namespace Alex\MultisiteBundle\Tests\Functional\DemoApp_Security;

use Alex\MultisiteBundle\Tests\Functional\AbstractAppKernel;
use Alex\MultisiteBundle\Tests\Functional\DemoApp_Security\DemoBundle\AlexMultisiteDemoBundle;

class AppKernel extends AbstractAppKernel
{
    /**
     * {@inheritdoc}
     */
    public function getAdditionalBundles()
    {
        return array(
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Alex\MultisiteBundle\Tests\Functional\DemoApp_Security\DemoBundle\AlexMultisiteDemoBundle(),
        );
    }
}
