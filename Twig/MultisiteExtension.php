<?php

namespace Alex\MultisiteBundle\Twig;

use Alex\MultisiteBundle\Branding\SiteContext;

class MultisiteExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $siteContext;

    public function __construct(SiteContext $siteContext)
    {
        $this->siteContext = $siteContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return array(
            'site_context' => $this->siteContext
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'multisite';
    }
}
