<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Alex\MultisiteBundle\Tests\Functional\WebTestCase;

class TemplatingTest extends WebTestCase
{
    public function testTemplateOverride()
    {
        $client = self::createClient();

        // foo - en_GB
        $crawler = $client->request('GET', 'http://foo.example.org/page-en');
        $this->assertContains('Foo header', $crawler->text());

        // bar - fr_FR
        $crawler = $client->request('GET', 'http://bar.example.org/page-fr');
        $this->assertContains('Default header', $crawler->text());

        // bar - de_DE
        $crawler = $client->request('GET', 'http://de.bar.example.org/page-de');
        $this->assertContains('German header', $crawler->text());
    }
}
