<?php

namespace App\Tests\Service;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Entity\Security;
use App\Service\EnclosureBuilderService;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    public function setUp()
    {
        self::bootKernel();

        $this->truncateEntities([
            Enclosure::class,
            Security::class,
            Dinosaur::class,
        ]);
    }

    public function testItBuildsAndPersistsEnclosure()
    {
        $enclosureBuilderService = self::$kernel->getContainer()
            ->get('test.'.EnclosureBuilderService::class);

        /** @var  Enclosure $enclosure */
        $enclosure = $enclosureBuilderService->buildEnclosure(2, 3);

        /** @var EntityManager $em */
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $count = (int) $em->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->where('s.enclosure = '.$enclosure->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(2, $count, 'Amount of security systems is not the same');

        $count = (int) $em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->where('d.enclosure = '.$enclosure->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $count, 'Amount of dinosaurs is not the same');
    }

    private function truncateEntities(array $entities): void
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    private function getEntityManager(): EntityManager
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
