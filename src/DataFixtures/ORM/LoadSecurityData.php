<?php

namespace App\DataFixtures\ORM;

use App\Entity\Enclosure;
use App\Entity\Security;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSecurityData extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // getReference() - object sharing between different Fixture classes;
        // loads object with reference 'herbivorous-enclosure' which was set bu class with order 1
        $herbivorousEnclosure = $this->getReference('herbivorous-enclosure');
        $this->addSecurity($herbivorousEnclosure, 'Fence', true);

        $carnivorousEnclosure = $this->getReference('carnivorous-enclosure');
        $this->addSecurity($carnivorousEnclosure, 'Electric fence', false);
        $this->addSecurity($carnivorousEnclosure, 'Guard tower', false);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

    private function addSecurity(Enclosure $enclosure, string $name, bool $isActive)
    {
        $enclosure->addSecurity(new Security($name, $isActive, $enclosure));
    }
}
