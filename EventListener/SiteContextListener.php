<?php

namespace Alex\MultisiteBundle\EventListener;

use Alex\MultisiteBundle\Branding\Branding;
use Alex\MultisiteBundle\Branding\SiteContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SiteContextListener
{
    private $siteContext;

    public function __construct (SiteContext $siteContext)
    {
        $this->siteContext = $siteContext;
    }

    public function onKernelRequest (GetResponseEvent $event)
    {
        $host = $event->getRequest()->getHost();
        $branding = $this->siteContext->getBrandingByHost($host);
        if ($branding instanceof Branding) {
            $this->siteContext->setCurrentBranding($branding);
        }
    }
}