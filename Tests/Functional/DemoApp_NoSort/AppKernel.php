<?php

namespace Alex\MultisiteBundle\Tests\Functional\DemoApp_NoSort;

use Alex\MultisiteBundle\Tests\Functional\DemoApp_NoSort\DemoBundle\AlexMultisiteDemoBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Alex\MultisiteBundle\AlexMultisiteBundle(),
            new \Alex\MultisiteBundle\Tests\Functional\DemoApp_NoSort\DemoBundle\AlexMultisiteDemoBundle(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }

    public function getCacheDir()
    {
        $dir = sys_get_temp_dir().'/test_ms_'.md5(uniqid().microtime());
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    public function getLogDir()
    {
        $dir = sys_get_temp_dir().'/test_ms_log_'.md5(uniqid().microtime());
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
    }
}
