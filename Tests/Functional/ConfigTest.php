<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Alex\MultisiteBundle\Tests\Functional\WebTestCase;

class ConfigTest extends WebTestCase
{
    public function testLocaleOnly()
    {
        $client = self::createClient();

        // foo - en_GB
        $crawler = $client->request('GET', 'http://foo.example.org/page-en');
        $this->assertContains('flag A', $crawler->text());

        // foo - fr_FR
        $crawler = $client->request('GET', 'http://foo.example.org/fr/page-fr');
        $this->assertNotContains('flag A', $crawler->text());

        // bar - fr_FR
        $crawler = $client->request('GET', 'http://bar.example.org/page-fr');
        $this->assertNotContains('flag B', $crawler->text());
    }
}
