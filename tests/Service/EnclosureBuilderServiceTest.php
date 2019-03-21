<?php

namespace App\Tests\Service;

use App\Entity\Dinosaur;
use App\Entity\Enclosure;
use App\Factory\DinosaurFactory;
use App\Service\EnclosureBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EnclosureBuilderServiceTest extends TestCase
{
    public function testItBuildsEnclosureWithSpecifications()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $dinoFactory = $this->createMock(DinosaurFactory::class);

        $dinoFactory->expects($this->exactly(5))
            ->method('growFromSpecification')
            ->with($this->isType('string'))
            ->willReturn(new Dinosaur());

        $em->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Enclosure::class));

        $em->expects($this->atLeastOnce())
            ->method('flush');

        $builder = new EnclosureBuilderService($em, $dinoFactory);
        $enclosure = $builder->buildEnclosure(1, 5);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(5, $enclosure->getDinosaurs());
    }
}
