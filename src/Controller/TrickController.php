<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Comment;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use App\Form\TrickType;
use App\Form\CommentType;

class TrickController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function getAllTricks(TrickRepository $trickRepository, Request $request)
    {
        $tricks = $trickRepository->findAll();
        $user = $this->getUser();

        return $this->render('pages/home/index.html.twig', [
            'tricks' => $tricks,
            'user' => $user
        ]);
    }

    #[Route('/trick/{id}', name: 'trick')]
    public function getTrick(Request $request, EntityManagerInterface $entityManager, TrickRepository $trickRepository, CommentRepository $commentRepository, int $id)
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);
        $comments = $commentRepository->findBy(['trick' => $id]);

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $author = $this->getUser()->getUsername();

            $comment->setAuthor($author);
            $comment->setDate(new \DateTime('now'));
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('trick', ['id' => $id]);
        }  

        return $this->render('pages/trick/index.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }

    #[Route('/addTrick', name: 'addTrick')]
    public function addTrick(Request $request, EntityManagerInterface $entityManager)
    {
        $trick = new Trick();

        $video = new Video();
        $trick->getVideos()->add($video);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video->setTrick($trick);
            $author = $this->getUser()->getUsername();
            $trick->setAuthor($author);

            $entityManager->persist($trick);
            $entityManager->flush();
            return $this->redirectToRoute('home');
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
            return $this->redirectToRoute('trick', ['id' => $id]);
        }    

        return $this->render('pages/updateTrick/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/deleteTrick/{id}', name: 'deleteTrick')]
    public function deleteTrick(EntityManagerInterface $entityManager, int $id, TrickRepository $trickRepository)
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);

        $entityManager->remove($trick);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}