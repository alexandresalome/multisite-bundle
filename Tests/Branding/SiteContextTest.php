<?php

namespace Alex\MultisiteBundle\Tests\Branding;

use Alex\MultisiteBundle\Branding\Branding;
use Alex\MultisiteBundle\Branding\SiteContext;

class SiteContextTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOption()
    {
        $context = $this->getSiteContext();
        $this->assertNull($context->getOption('foo'));
        $this->assertEquals('default', $context->getOption('inexisting', 'default'));
        $context->setCurrentLocale('en_GB');
        $this->assertTrue($context->getOption('foo'));
    }

    public function testNormalizePaths_WithDetails()
    {
        $context = $this->getSiteContext();

        $actual = array(
            'foo' => array('fr_FR' => '/french'),
            'bar' => array('de_DE' => '/german')
        );

        $expected = array(
            'foo' => array(
                'fr_FR' => '/french'
            ),
            'bar' => array(
                'de_DE' => '/de/german'
            ),
        );

        $this->assertEquals($expected, $context->normalizePaths($actual));
    }

    public function testNormalizePaths_WithLocale()
    {
        $context = $this->getSiteContext();

        $actual = array(
            'fr_FR' => '/french',
            'en_GB' => '/english',
            'de_DE' => '/german',
        );

        $expected = array(
            'foo' => array(
                'fr_FR' => '/french',
                'en_GB' => '/english',
            ),
            'bar' => array(
                'fr_FR' => '/french',
                'en_GB' => '/en/english',
                'de_DE' => '/de/german',
            ),
        );

        $this->assertEquals($expected, $context->normalizePaths($actual));
    }

    private function getSiteContext()
    {
        $foo = new Branding('foo', array(
            'fr_FR' => array('host' => 'foo.fr'),
            'en_GB' => array('foo' => true, 'host' => 'foo.co.uk'),
        ));

        $bar = new Branding('bar', array(
            'fr_FR' => array('host' => 'bar.com'),
            'en_GB' => array('host' => 'bar.com', 'prefix' => '/en'),
            'de_DE' => array('host' => 'bar.com', 'prefix' => '/de')
        ));

        return new SiteContext(array($foo, $bar), 'foo', 'fr_FR');
    }
}
