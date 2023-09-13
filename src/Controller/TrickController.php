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
    }
}