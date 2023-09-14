<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;

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

    #[Route('/addTrick', name: 'addTrick')]
    public function addTrick(Request $request, EntityManagerInterface $entityManager)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trick);
            $entityManager->flush();
        }    

        return $this->render('pages/addTrick/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/updateTrick/{id}', name: 'updateTrick')]
    public function updateTrick(Request $request, EntityManagerInterface $entityManager, int $id, TrickRepository $trickRepository)
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }    

        return $this->render('pages/updateTrick/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}