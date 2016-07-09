<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AbstractAppKernel extends Kernel
{
    protected $name;
    private $directory;

    public function getName()
    {
        if ($this->name) {
            return $this->name;
        }

        $class = get_class($this);
        $posEnd = strrpos($class, '\\');
        $posStart = strrpos($class, '\\', $posEnd - strlen($class) - 1) + 1;

        $this->name = strtolower(substr($class, $posStart, $posEnd - $posStart));

        return $this->name;
    }

    public function getDirectory()
    {
        if ($this->directory) {
            return $this->directory;
        }

        $this->directory = sys_get_temp_dir().'/test_ms_'.$this->getName();

        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0777, true);
        }

        return $this->directory;
    }

    public function getAdditionalBundles()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Alex\MultisiteBundle\AlexMultisiteBundle(),
        );

        return array_merge($bundles, $this->getAdditionalBundles());
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $refl = new \ReflectionClass($this);
        $dir = dirname($refl->getFilename());

        $loader->load($dir.'/config.yml');
    }

    public function getCacheDir()
    {
        return $this->getDirectory();
    }

    public function getLogDir()
    {
        return $this->getDirectory();
    }
}
