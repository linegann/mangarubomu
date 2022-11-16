<?php

namespace App\Controller;

use App\Entity\Character;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur Character
 * @Route("/character")
 */
class CharacterController extends AbstractController
{
    #[Route('/', name: 'character_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $characters = $em->getRepository(Character::class)->findAll();

        return $this->render('/character/index.html.twig',
            [ 'characters' => $characters ]
        );
    }
    /**
     * Show a [inventaire]
     *
     * @Route("/{id}", name="character_show", requirements={"id"="\d+"})
     *    note that the id must be an integer, above
     *
     * @param Integer $id
     */
    public function show(ManagerRegistry $doctrine, $id)
    {
        $characterRepo = $doctrine->getRepository(Character::class);
        $character = $characterRepo->find($id);

        if (!$character) {
            throw $this->createNotFoundException('The Character does not exist');
        }

        return $this->render('/character/show.html.twig',
            [ 'character' => $character ]
        );
    }
}
