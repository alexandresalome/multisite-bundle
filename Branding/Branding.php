<?php

namespace Alex\MultisiteBundle\Branding;

/**
 * Represents a branding in MultisiteBundle.
 *
 * $localesConfig is an associative array, indexed by locale:
 *
 *     $localesConfig = array(
 *       'fr_FR' => array('host' => 'example.org', 'prefix' => '/fr'),
 *       'en_GB' => array('host' => 'example.org'),
 *     );
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
    private $localesConfig;

    /**
     * Constructor of a branding.
     *
     * @param string $name
     * @param array  $localesConfig
     */
    public function __construct($name, array $localesConfig)
    {
        $this->name          = $name;
        $this->localesConfig = $localesConfig;
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
     * Tests if branding has a given locale.
     *
     * @return boolean
     */
    public function hasLocale($locale)
    {
        return isset($this->localesConfig[$locale]);
    }

    /**
     * Returns host configured for a locale, or null if not found.
     *
     * @param string $locale
     *
     * @return string|null returns a hostname or null if not found.
     */
    public function getHost($locale)
    {
        if (!isset($this->localesConfig[$locale]['host'])) {
            return null;
        }

        return $this->localesConfig[$locale]['host'];
    }

    /**
     * Prefixes the path for a given locale, if a prefix is configured.
     *
     * ``prefixPath`` will return the path if no prefix is configured.
     *
     * @param string $locale
     * @param string $path
     *
     * @return string
     */
    public function prefixPath($locale, $path)
    {
        if (isset($this->localesConfig[$locale]['prefix'])) {
            return $this->localesConfig[$locale]['prefix'].$path;
        }

        return $path;
    }

    /**
     * Returns value of an option.
     *
     * @param string $locale
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($locale, $name, $default = null)
    {
        return isset($this->localesConfig[$locale][$name]) ? $this->localesConfig[$locale][$name] : $default;
    }
}
