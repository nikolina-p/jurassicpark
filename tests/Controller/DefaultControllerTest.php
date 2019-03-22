<?php

namespace App\Tests\Controller;

use App\DataFixtures\ORM\LoadBasicParkData;
use App\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testEnclosuresAreShownOnHomepage()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class,
        ]);

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/lucky');
        $this->assertStatusCode(200, $client);

        $table = $crawler->filter('.table-enclosures');
        $this->assertCount(3, $table->filter('tbody tr'));
    }
}
