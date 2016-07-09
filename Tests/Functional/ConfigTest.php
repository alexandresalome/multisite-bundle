<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Alex\MultisiteBundle\Tests\Functional\WebTestCase;

class ConfigTest extends WebTestCase
{
    public function testLocaleOnly()
    {
        $client = self::createClient();

        // foo - en_GB
        $client->request('GET', 'http://foo.example.org/page-en');
        $this->assertContains('flag A', $client->getResponse()->getContent());

        // foo - fr_FR
        $client->request('GET', 'http://foo.example.org/fr/page-fr');
        $this->assertNotContains('flag A', $client->getResponse()->getContent());

        // bar - fr_FR
        $client->request('GET', 'http://bar.example.org/page-fr');
        $this->assertNotContains('flag B', $client->getResponse()->getContent());
    }

    public function testNoSort()
    {
        $client = self::createClient('DemoApp_NoSort');

        $client->request('GET', '/bar');
        $this->assertContains('Route A', $client->getResponse()->getContent());
    }
}
