<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Character;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Builder\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    #[Route('/album', name: 'album_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Albums</title>
    </head>
    <body>
        <h1>Liste des Albums</h1>
        <p>Check moi ça frérot :</p>
        <ul>';
        
        $em= $doctrine->getManager();
        $albums = $em->getRepository(Album::class)->findAll();
        foreach ($albums as $album) {
           $htmlpage .= '<li>
            <a href="/album/'.$album->getid().'">'.$album->getTitle().'</a></li>';
         }
        $htmlpage .= '</ul>';

        $htmlpage .= '</body></html>';
        
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
            );
    }

    /**
     * Show a [inventaire]
     *
     * @Route("/album/{id}", name="album_show", requirements={"id"="\d+"})
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

        $res = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Album n° '.$album->getId().'</title>
    </head>
    <body>
        <h2>' . $album->getTitle() . '</h2>
        <ul>
        <dl>';

        $res .= '<dt>Contient :</dt>';
        $characters = $album->getCharacters();
        foreach ($characters as $character) {
            $url = $this->generateUrl(
                'album_show',
                ['id' => $album->getId()]);
           $res .= '<li>
            <a href="'. $url .'">'.$character->getName().'</a></li>';
         }
        $res .= '</dl>';
        $res .= '<p/><a href="' . $this->generateUrl('album_index') . '">Back</a>';
        $res .= '</ul></body></html>';

        return new Response(
                $res,
                Response::HTTP_OK,
                array('content-type' => 'text/html')
                );
    }
}
