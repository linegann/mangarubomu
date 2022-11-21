<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;

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

    #[Route('/new', name: 'app_album_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AlbumRepository $albumRepository): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);

            $this->addFlash('message', 'Album creation success');

            return $this->redirectToRoute('album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'album_show', methods: ['GET'])]
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

    #[Route('/{id}/edit', name: 'app_album_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        dump($album);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);

            $this->addFlash('message', 'Album edition success');

            return $this->redirectToRoute('album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_album_delete', methods: ['POST'])]
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $albumRepository->remove($album, true);

            $this->addFlash('message', 'Album deletion success');
        }

        return $this->redirectToRoute('album_index', [], Response::HTTP_SEE_OTHER);
    }
}
