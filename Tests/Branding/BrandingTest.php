<?php

namespace Alex\MultisiteBundle\Tests\Branding;

use Alex\MultisiteBundle\Branding\Branding;

class BrandingTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $branding = new Branding('foo', array());
        $this->assertEquals('foo', $branding->getName());
    }

    public function testHasLocale()
    {
        $branding = new Branding('foo', array(
            'fr_FR' => array('host' => 'foo')
        ));

        $this->assertFalse($branding->hasLocale('en_GB'));
        $this->assertTrue($branding->hasLocale('fr_FR'));
    }

    public function testGetHost()
    {
        $branding = new Branding('foo', array(
            'fr_FR' => array('host' => 'foo')
        ));

        $this->assertEquals('foo', $branding->getHost('fr_FR'));
        $this->assertNull($branding->getHost('en_GB'));
    }

    public function testPrefixPath()
    {
        $branding = new Branding('foo', array(
            'fr_FR' => array('prefix' => '/test')
        ));

        $this->assertEquals('/test/fr', $branding->prefixPath('fr_FR', '/fr'));
        $this->assertEquals('/fr', $branding->prefixPath('en_GB', '/fr'));
    }
}
