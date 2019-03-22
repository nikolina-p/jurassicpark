<?php

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testEnclosuresAreShownOnHomepage()
    {
        $client = $this->makeClient();

        $crawler = $client->request('GET', '/lucky');

        $this->assertStatusCode(200, $client);
    }

}