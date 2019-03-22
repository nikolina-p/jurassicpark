<?php

namespace App\Tests\Service;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Entity\Security;
use App\Factory\DinosaurFactory;
use App\Service\EnclosureBuilderService;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    public function setUp()
    {
        self::bootKernel();

        $this->truncateEntities();
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
            //->where('s.enclosure = '.$enclosure->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(2, $count, 'Amount of security systems is not the same');

        $count = (int) $em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            //->where('d.enclosure = '.$enclosure->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $count, 'Amount of dinosaurs is not the same');
    }

    public function testItBuildsEnclosurePartialMocking()
    {
        $dinosaurFactory = $this->createMock(DinosaurFactory::class);
        $dinosaurFactory->expects($this->exactly(3))
            ->method('growFromSpecification')
            ->with($this->isType('string'))
            ->willReturnCallback(function($spec) {
                return new Dinosaur();
            }); //empty database is precondition

        $enclosureBuilderService = new EnclosureBuilderService($this->getEntityManager(), $dinosaurFactory);
        $enclosure = $enclosureBuilderService->buildEnclosure();

        /** @var EntityManager $em */
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $count = (int) $em->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            //->where('s.enclosure = '.$enclosure->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $count, 'Amount of security systems is not the same');

        $count = (int) $em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            //->where('d.enclosure = '.$enclosure->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $count, 'Amount of dinosaurs is not the same');
    }

    private function truncateEntities(): void
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
