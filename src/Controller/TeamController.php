<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur Team
 * @Route("/team")
 */
class TeamController extends AbstractController
{
    #[Route('/', name: 'team_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $teams = $em->getRepository(Team::class)->findAll();

        return $this->render('/team/index.html.twig',
            [ 'teams' => $teams ]
        );
    }
}
