<?php

namespace Alex\MultisiteBundle\Tests\Functional;

use Alex\MultisiteBundle\Tests\Functional\WebTestCase;

class SecurityTest extends WebTestCase
{
    public function testLogin()
    {
        $client = self::createClient('DemoApp_Security');

        $crawler = $client->request('GET', '/bar-en/');
        $this->assertContains('Homepage bar/en_GB', $client->getResponse()->getContent());

        $crawler = $client->click($crawler->filter('a:contains("Login")')->eq(0)->link());
        $this->assertContains('Login form', $client->getResponse()->getContent());

        $form = $crawler->selectButton('Submit')->form(array(
            '_username' => 'user',
            '_password' => 'user'
        ));

        $client->submit($form);

        $crawler = $client->followRedirect();
        // make sure we stayed on same site
        $this->assertContains('Homepage bar/en_GB', $client->getResponse()->getContent());
    }
}
