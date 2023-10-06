<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\TrickManager;

use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Comment;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use App\Repository\VideoRepository;
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

    #[Route('/trick/{slug}', name: 'show_trick')]
    public function getTrick(Request $request, EntityManagerInterface $entityManager, TrickRepository $trickRepository, CommentRepository $commentRepository, VideoRepository $videoRepository, string $slug)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $author = $this->getUser()->getUsername();

            $comment->setAuthor($author);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('show_trick', ['slug' => $slug]);
        }  

        return $this->render('pages/trick/index.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    #[Route('/addTrick', name: 'add_trick')]
    public function addTrick(Request $request, EntityManagerInterface $entityManager, TrickRepository $trickRepository, TrickManager $trickManager)
    {
        $trick = new Trick();

        $video = new Video();
        $trick->getVideos()->add($video);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $getTrickByName = $trickRepository->findOneBy(['name' => $trick->getName()]);
            // if($getTrickByName == null){
            //     $video->setTrick($trick);
            //     $author = $this->getUser()->getUsername();
            //     $trick->setAuthor($author);
            //     $trick = $trickManager->createVideoUrl($trick);
            //     $trick = $trickManager->createSlug($trick);

            //     $entityManager->persist($trick);
            //     $entityManager->flush();
            //     $this->addFlash('success', 'Votre figure a bien été créée !');
            //     return $this->redirectToRoute('home');   
            // } else {
            //     $errors[] = 'La figure que vous essayez de créer existe déjà.';
            // }
            $video->setTrick($trick);
            $author = $this->getUser()->getUsername();
            $trick->setAuthor($author);
            $trick = $trickManager->createVideoUrl($trick);
            $trick = $trickManager->createSlug($trick);

            $entityManager->persist($trick);
            $entityManager->flush();
            $this->addFlash('success', 'Votre figure a bien été créée !');
            return $this->redirectToRoute('home');  
        }    

        return $this->render('pages/trick_form/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/updateTrick/{slug}', name: 'update_trick')]
    public function updateTrick(Request $request, EntityManagerInterface $entityManager, string $slug, TrickRepository $trickRepository, TrickManager $trickManager)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $trickManager->createVideoUrl($trick);
            $trick = $trickManager->createSlug($trick);
            $entityManager->flush();
            return $this->redirectToRoute('show_trick', ['slug' => $slug]);
        }    

        return $this->render('pages/trick_form/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/deleteTrick/{slug}', name: 'delete_trick')]
    public function deleteTrick(EntityManagerInterface $entityManager, string $slug, TrickRepository $trickRepository)
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);

        $entityManager->remove($trick);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}