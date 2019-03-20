<?php

namespace App\Tests\Entity;


use App\Entity\Enclosure;
use App\Entity\Dinosaur;
use App\Exception\DinosaursAreRunningRampantException;
use App\Exception\NotABuffetException;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;


class EnclosureTest extends TestCase
{
    public function testItHasNoDinosaursByDefault()
    {
        $enclosure = new Enclosure();
        $this->assertEmpty($enclosure->getDinosaurs());
    }

    public function testItAddsDinosaurs()
    {
        $enclosure = new Enclosure(true);
        $enclosure->addDinosaur(new Dinosaur());
        $enclosure->addDinosaur(new Dinosaur());
        $this->assertCount(2, $enclosure->getDinosaurs());
    }

    public function testItDoesNotAllowToAddDinosToUnsecureEnclosures()
    {
        $enclosure = new Enclosure();

        $this->expectException(DinosaursAreRunningRampantException::class);
        $this->expectExceptionMessage('Are you craaazy?!?');

        $enclosure->addDinosaur(new Dinosaur());
    }

    public function testItDoesNotAllowCarnivorousDinosToMixWithHerbivores()
    {
        $enclosure = new Enclosure(true);

        $this->expectException(NotABuffetException::class);

        $enclosure->addDinosaur(new Dinosaur());
        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));
    }

    /**
     * @expectedException \App\Exception\NotABuffetException
     */
    public function testNoHerbivoresInCarnivorousEnclosure()
    {
        $enclosure = new Enclosure(true);

        $enclosure->addDinosaur(new Dinosaur("Velociraptor", true));
        $enclosure->addDinosaur(new Dinosaur('Prontosaur', false));
    }
}
