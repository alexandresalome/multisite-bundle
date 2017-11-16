<?php

namespace Alex\MultisiteBundle\Branding;

/**
 * Represents the multisite context.
 *
 * This service is stateful, because it contains a "current" branding/locale.
 */
class SiteContext
{
    /**
     * @var Branding[]
     */
    protected $brandings = array();

    /**
     * @var string
     */
    protected $defaultBrandingName;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var Branding
     */
    protected $currentBranding;

    /**
     * @var string
     */
    protected $currentLocale;

    /**
     * Creates a new instance.
     *
     * @param Branding[] $brandings
     * @param string     $defaultBrandingName
     * @param string     $defaultLocale
     */
    public function __construct(array $brandings, $defaultBrandingName, $defaultLocale)
    {
        foreach ($brandings as $branding) {
            $this->addBranding($branding);
        }

        $this->defaultBrandingName = $defaultBrandingName;
        $this->defaultLocale   = $defaultLocale;
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
     * Changes the current locale.
     *
     * @param string $locale
     *
     * @return SiteContext
     */
    public function setCurrentLocale($locale)
    {
        $this->currentLocale = $locale;

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
     * Returns current locale.
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        if (null === $this->currentLocale) {
            return $this->defaultLocale;
        }

        return $this->currentLocale;
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
     * Returns brandings with a given locale.
     *
     * @param string $locale
     *
     * @return Branding[]
     */
    public function getBrandingsWithLocale($locale)
    {
        $result = array();
        foreach ($this->brandings as $branding) {
            if ($branding->hasLocale($locale)) {
                $result[] = $branding;
            }
        }

        return $result;
    }

    /**
     * Converts an array used in annotation to an associative array branding/locale.
     *
     * @param array $paths
     *
     * @return array
     */
    public function normalizePaths(array $paths)
    {
        $result = array();

        foreach ($paths as $key => $value) {
            // key is locale
            if (is_string($value)) {
                foreach ($this->getBrandingsWithLocale($key) as $branding) {
                    $result[$branding->getName()][$key] = $branding->prefixPath($key, $value);
                }
            }

            // key is branding
            if (is_array($value)) {
                foreach ($value as $locale => $path) {
                    $result[$key][$locale] = $this->getBranding($key)->prefixPath($locale, $path);
                }
            }
        }

        return $result;
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
        return $this->getCurrentBranding()->getOption($this->getCurrentLocale(), $name, $default);
    }

    /**
     * Adds a new branding to the context.
     *
     * @param Branding $branding
     *
     * @return SiteContext
     */
    protected function addBranding(Branding $branding)
    {
        $this->brandings[] = $branding;

        return $this;
    }
}
