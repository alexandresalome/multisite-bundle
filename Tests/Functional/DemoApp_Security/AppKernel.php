<?php

namespace Alex\MultisiteBundle\Tests\Functional\DemoApp_Security;

use Alex\MultisiteBundle\Tests\Functional\DemoApp_Security\DemoBundle\AlexMultisiteDemoBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    private $dir;

    public function getDir()
    {
        if ($this->dir) {
            return $this->dir;
        }

        $this->dir = sys_get_temp_dir().'/test_ms_security_'.md5(uniqid().microtime());
        mkdir($this->dir, 0777, true);

        return $this->dir;
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Alex\MultisiteBundle\AlexMultisiteBundle(),
            new \Alex\MultisiteBundle\Tests\Functional\DemoApp_Security\DemoBundle\AlexMultisiteDemoBundle(),
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
        return $this->getDir();
    }

    public function getLogDir()
    {
        return $this->getDir();
    }
}
