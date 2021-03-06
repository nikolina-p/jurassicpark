<?php

namespace App\Service;

use App\Entity\Enclosure;
use App\Entity\Security;
use App\Factory\DinosaurFactory;
use Doctrine\ORM\EntityManagerInterface;

class EnclosureBuilderService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DinosaurFactory
     */
    private $dinosaurFactory;

    public function __construct(EntityManagerInterface $entityManager, DinosaurFactory $dinosaurFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->dinosaurFactory = $dinosaurFactory;
    }

    public function buildEnclosures(int $noOfEnclosures = 1, int $numberOfSecuritySystems = 1, int $numberOfDinosaurs = 3): array
    {
        $enclosures = [];
        for ($i = 0; $i<$noOfEnclosures; $i++) {
            $enclosure = new Enclosure();
            $this->addSecuritySystems($numberOfSecuritySystems, $enclosure);
            $this->addDinosaurs($numberOfDinosaurs, $enclosure);
            $enclosures[] = $enclosure;
        }
        return $enclosures;
    }

    public function buildEnclosure(int $numberOfSecuritySystems = 1, int $numberOfDinosaurs = 3): Enclosure
    {
        $enclosure = new Enclosure();
        $this->addSecuritySystems($numberOfSecuritySystems, $enclosure);
        $this->addDinosaurs($numberOfDinosaurs, $enclosure);

        $this->entityManager->persist($enclosure);
        $this->entityManager->flush();

        return $enclosure;
    }

    private function addSecuritySystems(int $numberOfSecuritySystems, Enclosure $enclosure)
    {
        $securityNames = ['Fence', 'Electric fence', 'Guard tower'];
        for ($i = 0; $i < $numberOfSecuritySystems; $i++) {
            $securityName = $securityNames[array_rand($securityNames)];
            $security = new Security($securityName, true, $enclosure);
            $enclosure->addSecurity($security);
        }
    }
    private function addDinosaurs(int $numberOfDinosaurs, Enclosure $enclosure)
    {
        $lengths = ['small', 'large', 'huge'];
        $diets = ['herbivore', 'carnivorous'];
        $diet = $diets[array_rand($diets)];
        for($i = 0; $i<$numberOfDinosaurs; $i++) {
            $length = $lengths[array_rand($lengths)];
            $specification = "{$length} {$diet} dinosaur";
            $dinosaur = $this->dinosaurFactory->growFromSpecification($specification);
            $dinosaur->setEnclosure($enclosure);

            $enclosure->addDinosaur($dinosaur);
        }
    }
}
