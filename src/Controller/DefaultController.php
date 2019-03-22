<?php

namespace App\Controller;

use App\Entity\Enclosure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/lucky")
     */
    public function showHomePage()
    {
        $enclosures = $this->entityManager->getRepository(Enclosure::class)->findAll();

        return $this->render('index.html.twig', [
           'enclosures' => $enclosures,
        ]);
    }
}
