<?php

namespace App\Controller;

use App\Entity\Enclosure;
use App\Factory\DinosaurFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/lucky", name="homepage")
     */
    public function showHomePage()
    {
        $enclosures = $this->entityManager->getRepository(Enclosure::class)->findAll();

        return $this->render('index.html.twig', [
           'enclosures' => $enclosures,
        ]);
    }

    /**
     * @Route("/grow", name="grow_dinosaur", methods={"POST"})
     */
    public function growAction(Request $request, DinosaurFactory $dinosaurFactory)
    {
        $manager = $this->getDoctrine()->getManager();
        $enclosure = $manager->getRepository(Enclosure::class)->find($request->get('enclosure'));

        $specification = $request->get('specification');

        $dino = $dinosaurFactory->growFromSpecification($specification);
        $dino->setEnclosure($enclosure);
        $enclosure->addDinosaur($dino);

        $manager->flush();

        return $this->redirectToRoute('homepage');
    }
}
