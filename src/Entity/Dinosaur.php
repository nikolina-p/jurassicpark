<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Enclosure;

/**
 * @ORM\Entity
 * @ORM\Table(name="dinosaurs")
 */
class Dinosaur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $length = 0;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $genus;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isCarnivorous;

    /**
     * @var Enclosure
     * @ORM\ManyToOne(targetEntity="App\Entity\Enclosure", inversedBy="dinosaurs")
     */
    private $enclosure;

    const LARGE = 10;
    const HUGE = 30;

    public function __construct(string $genus = 'Unknown', bool $isCarnivorous = false)
    {
        $this->genus = $genus;
        $this->isCarnivorous = $isCarnivorous;
    }

    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);

        return $dinosaur;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getGenus(): string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): void
    {
        $this->genus = $genus;
    }

    public function getEnclosure(): Enclosure
    {
        return $this->enclosure;
    }

    public function setEnclosure(Enclosure $enclosure): void
    {
        $this->enclosure = $enclosure;
    }

    public function getSpecification(): string
    {
        return sprintf(
            'The %s %s dinosaur is %d meters long',
            $this->genus,
            $this->isCarnivorous ? 'carnivorous' : 'non-carnivorous',
            $this->length
        );
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    public function isCarnivorous()
    {
        return $this->isCarnivorous;
    }
}
