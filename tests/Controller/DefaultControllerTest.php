<?php

namespace App\Tests\Controller;

use App\DataFixtures\ORM\LoadBasicParkData;
use App\DataFixtures\ORM\LoadEnclosureData;
use App\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Throwable;

class DefaultControllerTest extends WebTestCase
{
    private $client;

    public function testEnclosuresAreShownOnHomepage()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
        ]);

        $this->client = $this->makeClient();

        $crawler = $this->client->request('GET', '/lucky');
        $this->assertStatusCode(200, $this->client);

        $table = $crawler->filter('.table-enclosures');
        $this->assertCount(2, $table->filter('tbody tr'));
    }

    public function testThatThereIsAnAlarmButtonWithoutSecurity()
    {
        $fixtures = $this->loadFixtures([
            LoadEnclosureData::class,
            LoadSecurityData::class,
        ])->getReferenceRepository();

        $this->client = $this->makeClient();
        $crawler = $this->client->request("GET", '/lucky');

        $enclosure = $fixtures->getReference('carnivorous-enclosure');
        $selector = sprintf('#enclosure-%s .button-alarm', $enclosure->getId());

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
        $this->assertCount(1, $crawler->filter('.button-alarm'));
    }

    protected function onNotSuccessfulTest(Throwable $t)
    {
        echo $t->getMessage().' Line: '.$t->getLine().' \n\n';
        var_dump($this->client->getResponse()->getContent());
    }
}
