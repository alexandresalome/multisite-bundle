<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Alex\MultisiteBundle\Tests\Functional\WebTestCase;

class TemplatingTest extends WebTestCase
{
    public function testTemplateOverride()
    {
        $client = self::createClient();

        // foo - en_GB
        $client->request('GET', 'http://foo.example.org/page-en');
        $this->assertContains('Foo header', $client->getResponse()->getContent());

        // bar - fr_FR
        $client->request('GET', 'http://bar.example.org/page-fr');
        $this->assertContains('Default header', $client->getResponse()->getContent());

        // bar - de_DE
        $client->request('GET', 'http://de.bar.example.org/page-de');
        $this->assertContains('German header', $client->getResponse()->getContent());
    }
}
