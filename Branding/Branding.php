<?php

namespace Alex\MultisiteBundle\Branding;

/**
 * Represents a branding in MultisiteBundle.
 *
 * $config array must has 'hosts' key.
 *
 */
class Branding
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $config;

    /**
     * Constructor of a branding.
     *
     * @param string $name
     * @param array  $config
     */
    public function __construct($name, array $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    /**
     * Returns the name of the branding.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns host configured for this branding.
     *
     * @return array returns an array with hostname.
     */
    public function getHosts()
    {
        return isset($this->config['hosts']) ? $this->config['hosts'] : array();
    }

    /**
     * Returns value of an option.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return isset($this->config[$name]) ? $this->config[$name] : $default;
    }

    /**
     * Returns if a host is configured for this branding
     *
     * @param string $host
     * @return bool
     */
    public function hasHost($host)
    {
        foreach ($this->getHosts() as $value) {
            if ($host === $value) {
                return true;
            }
        }

        return false;
    }
}
