<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Entity\Membre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Controleur Album
 * @Route("/")
 */
class AlbumController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function home()
    {
        return $this->render('/index.html.twig', []);
    }

    #[Route('/album', name: 'album_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $albums = $em->getRepository(Album::class)->findAll();

        dump($albums);

        return $this->render('/album/index.html.twig',
            [ 'albums' => $albums ]
        );
    }

    #[Route('/album/new/{id}', name: 'app_album_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AlbumRepository $albumRepository, Membre $membre): Response
    {
        $album = new Album();
        $album->setMembre($membre);
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);

            $this->addFlash('message', 'Album creation success');

            return $this->redirectToRoute('app_membre_show', ['id' => $membre->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }
    
    #[Route('/album/{id}', name: 'album_show', methods: ['GET'])]
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

    #[Route('/album/{id}/edit', name: 'app_album_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        dump($album);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->add($album, true);

            $this->addFlash('message', 'Album edition success');

            return $this->redirectToRoute('album_show', ['id' => $album->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/album/{id}', name: 'app_album_delete', methods: ['POST'])]
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        $membre = $album->getMembre();
        $membreid = $membre->getId();

        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $albumRepository->remove($album, true);

            $this->addFlash('message', 'Album deletion success');
        }

        return $this->redirectToRoute('app_membre_show', ['id' => $membreid], Response::HTTP_SEE_OTHER);
    }
}
