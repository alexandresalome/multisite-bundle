<?php

namespace Alex\MultisiteBundle\Branding;

/**
 * Represents the multisite context.
 *
 * This service is stateful, because it contains a "current" branding.
 */
class SiteContext
{
    /**
     * @var Branding[]
     */
    private $brandings = array();

    /**
     * @var string
     */
    private $defaultBrandingName;

    /**
     * @var Branding
     */
    private $currentBranding;

    /**
     * Creates a new instance.
     *
     * @param Branding[] $brandings
     * @param string     $defaultBrandingName
     */
    public function __construct(array $brandings, $defaultBrandingName)
    {
        foreach ($brandings as $branding) {
            $this->addBranding($branding);
        }

        $this->defaultBrandingName = $defaultBrandingName;
    }

    /**
     * Changes the current branding of site context.
     *
     * @param Branding $branding
     *
     * @return SiteContext
     */
    public function setCurrentBranding(Branding $branding)
    {
        $this->currentBranding = $branding;

        return $this;
    }

    /**
     * Returns name of the current branding.
     *
     * @return string
     */
    public function getCurrentBrandingName()
    {
        return $this->getCurrentBranding()->getName();
    }

    /**
     * Returns current branding.
     *
     * @return Branding
     */
    public function getCurrentBranding()
    {
        if (null === $this->currentBranding) {
            return $this->getBranding($this->defaultBrandingName);
        }

        return $this->currentBranding;
    }

    /**
     * Returns a given branding.
     *
     * @param string $name
     *
     * @return Branding
     */
    public function getBranding($name)
    {
        foreach ($this->brandings as $branding) {
            if ($branding->getName() === $name) {
                return $branding;
            }
        }

        $names = array_map(function ($branding) {
            return $branding->getName();
        }, $this->brandings);

        throw new \InvalidArgumentException(sprintf('No branding named "%s". Available are: %s.', $name, implode(', ', $names)));
    }

    /**
     * Returns brandings
     *
     * @return Branding[]
     */
    public function getBrandings()
    {
        return $this->brandings;
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
        return $this->getCurrentBranding()->getOption($name, $default);
    }

    /**
     * Adds a new branding to the context.
     *
     * @param Branding $branding
     *
     * @return SiteContext
     */
    private function addBranding(Branding $branding)
    {
        $this->brandings[] = $branding;

        return $this;
    }

    /**
     * Returns the branding associated to the host
     *
     * @param string $host
     *
     * @return Branding|null
     */
    public function getBrandingByHost($host)
    {
        foreach ($this->brandings as $branding) {
            if ($branding->hasHost($host)) {
                return $branding;
            }
        }

        return null;
    }
}
