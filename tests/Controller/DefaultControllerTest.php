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

    public function testFormGrowsADinosaurFromSpecification()
    {
        $this->loadFixtures([
            LoadEnclosureData::class,
            LoadSecurityData::class,
        ]);

        $this->client = $this->makeClient();
        //$this->client->followRedirect();

        $crawler = $this->client->request('GET', '/lucky');

        //first check if the form loads
        $this->assertStatusCode(200, $this->client);
        $this->assertCount(1, $crawler->filter('.dino-form'));

        //submit the form
        $form = $crawler->selectButton('Grow dinosaur')->form();
        $form['enclosure']->select(2);
        $form['specification']->setValue("large herbivore");
        $this->client->submit($form);

        //$this->client->followRedirect();

        $this->assertEquals(3, substr($this->client->getResponse()->getStatusCode(), 0, 1));
    }

    protected function onNotSuccessfulTest(Throwable $t)
    {
        echo $t->getMessage().' Line: '.$t->getLine().' \n\n';
        var_dump($this->client->getResponse()->getContent());
    }
}
