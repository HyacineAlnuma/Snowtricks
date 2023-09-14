<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    #[Route('/tricks', name: 'tricks')]
    public function getAllTricks(TrickRepository $trickRepository)
    {
        $tricks = $trickRepository->findAll();

        return $this->render('pages/tricks/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    #[Route('/trick/{id}', name: 'trick')]
    public function getTrick(TrickRepository $trickRepository, int $id)
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);

        return $this->render('pages/trick/index.html.twig', [
            'trick' => $trick
        ]);
    }
}