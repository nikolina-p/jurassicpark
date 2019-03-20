<?php
/**
 * Created by PhpStorm.
 * User: nikolina
 * Date: 3/11/19
 * Time: 4:26 PM
 */

namespace App\Factory;

use App\Entity\Dinosaur;
use App\Service\DinosaurLengthDeterminator;
use function Symfony\Component\Console\Tests\Command\createClosure;

class DinosaurFactory
{
    private $lengthDeterminator;

    public function __construct(DinosaurLengthDeterminator $lengthDeterminator)
    {
        $this->lengthDeterminator = $lengthDeterminator;
    }

    private function createDinosaur(string $genus, bool $isCarnivorous, int $length)
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);

        $dinosaur->setLength($length);

        return $dinosaur;
    }

    public function growVelociraptor(int $length): Dinosaur
    {
        $velociraptor = $this->createDinosaur("Velociraptor", true, $length);

        return $velociraptor;
    }

    public function growFromSpecification(string $specification): Dinosaur
    {
        $codeName = 'InG-' . random_int(1, 99999);
        $length = random_int(1, Dinosaur::LARGE - 1);
        $isCarnivorous = false;

        $length = $this->lengthDeterminator->getLengthFromSpecification($specification);

        if (stripos($specification, 'carnivorous') !== false) {
        $isCarnivorous = true;
        }

        $dinosaur = $this->createDinosaur($codeName, $isCarnivorous, $length);

        return $dinosaur;
    }
}
