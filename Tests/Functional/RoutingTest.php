<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Alex\MultisiteBundle\Tests\Functional\WebTestCase;

class RoutingTest extends WebTestCase
{
    public function testDefaultBehavior()
    {
        $client = self::createClient();

        $urls = array(
            'http://foo.example.org',
            'http://bar.example.org',
            'http://de.bar.example.org',
        );

        // verify every site has homepage, because homepage is not
        // multisite
        foreach ($urls as $url) {
            $crawler = $client->request('GET', $url);
            $this->assertContains('Test homepage', $crawler->text(), "Test ".$url);
        }
    }

    public function testLocaleOnly()
    {
        $client = self::createClient();

        // foo - en_GB
        $crawler = $client->request('GET', 'http://foo.example.org/page-en');
        $this->assertContains('branding: foo, locale: en_GB', $crawler->text());

        // foo - fr_FR
        $crawler = $client->request('GET', 'http://foo.example.org/fr/page-fr');
        $this->assertContains('branding: foo, locale: fr_FR', $crawler->text());

        // bar - fr_FR
        $crawler = $client->request('GET', 'http://bar.example.org/page-fr');
        $this->assertContains('branding: bar, locale: fr_FR', $crawler->text());
    }
}
