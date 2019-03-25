<?php

namespace App\Tests\Controller;

use App\DataFixtures\ORM\LoadBasicParkData;
use App\DataFixtures\ORM\LoadEnclosureData;
use App\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testEnclosuresAreShownOnHomepage()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
        ]);

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/lucky');
        $this->assertStatusCode(200, $client);

        $table = $crawler->filter('.table-enclosures');
        $this->assertCount(2, $table->filter('tbody tr'));
    }

    public function testThatThereIsAnAlarmButtonWithoutSecurity()
    {
        $fixtures = $this->loadFixtures([
            LoadEnclosureData::class,
            LoadSecurityData::class,
        ])->getReferenceRepository();

        $client = $this->makeClient();
        $crawler = $client->request("GET", '/lucky');

        $enclosure = $fixtures->getReference('carnivorous-enclosure');
        $selector = sprintf('#enclosure-%s .button-alarm', $enclosure->getId());
        var_dump($enclosure->getId());

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
        $this->assertCount(1, $crawler->filter('.button-alarm'));
    }
}
