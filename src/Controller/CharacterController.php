<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'app_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CharacterRepository $characterRepository): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $characterRepository->add($character, true);

            return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('character/new.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'character_show', methods: ['GET'])]
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

    #[Route('/{id}/edit', name: 'app_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character, CharacterRepository $characterRepository): Response
    {
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $characterRepository->add($character, true);

            return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('character/edit.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_character_delete', methods: ['POST'])]
    public function delete(Request $request, Character $character, CharacterRepository $characterRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->request->get('_token'))) {
            $characterRepository->remove($character, true);
        }

        return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
}
