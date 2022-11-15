<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Character;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Builder\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur Album
 * @Route("/album")
 */
class AlbumController extends AbstractController
{
    #[Route('/', name: 'album_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $albums = $em->getRepository(Album::class)->findAll();

        dump($albums);

        return $this->render('/album/index.html.twig',
            [ 'albums' => $albums ]
        );
    }

    /**
     * Show a [inventaire]
     *
     * @Route("/{id}", name="album_show", requirements={"id"="\d+"})
     *    note that the id must be an integer, above
     *
     * @param Integer $id
     */
    public function show(ManagerRegistry $doctrine, $id)
    {
        $albumRepo = $doctrine->getRepository(Album::class);
        $album = $albumRepo->find($id);

        if (!$album) {
            throw $this->createNotFoundException('The Album does not exist');
        }

        return $this->render('/album/show.html.twig',
            [ 'album' => $album ]
        );
    }
}
