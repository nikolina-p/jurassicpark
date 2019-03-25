<?php

namespace App\DataFixtures\ORM;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Service\EnclosureBuilderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBasicParkData extends Fixture
{
    private $enclosureBuilderService;

    public function __construct(EnclosureBuilderService $enclosureBuilderService)
    {
        $this->enclosureBuilderService = $enclosureBuilderService;
    }

    public function load(ObjectManager $manager)
    {
        $enclosures = $this->enclosureBuilderService->buildEnclosures(2, 2, 3);

        foreach ($enclosures as $enclosure) {
            $manager->persist($enclosure);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

    private function addDinosaur(
        ObjectManager $manager,
        Enclosure $enclosure,
        string $genus,
        bool $isCarnivorous,
        int $length
    )
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setEnclosure($enclosure);
        $dinosaur->setLength($length);
        $manager->persist($dinosaur);
    }
}
