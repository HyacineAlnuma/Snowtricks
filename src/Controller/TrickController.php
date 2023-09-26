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

    #[Route('/trick/{id}', name: 'show_trick')]
    public function getTrick(Request $request, EntityManagerInterface $entityManager, TrickRepository $trickRepository, CommentRepository $commentRepository, VideoRepository $videoRepository, int $id)
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);
        $comments = $commentRepository->findBy(['trick' => $id]);

        $videosCollection = $videoRepository->findBy(['Trick' => $id]);
        $pattern = '/.*src=\\"(.*)" title(.*)/';
        $videos = [];
        foreach($videosCollection as $video){
            $videosCollection = preg_match($pattern, $video->getVideoEmbed(), $match);
            $videos[] = $match[1];
        }

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            $author = $this->getUser()->getUsername();

            $comment->setAuthor($author);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('show_trick', ['id' => $id]);
        }  

        return $this->render('pages/trick/index.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'videos' => $videos,
            'form' => $form->createView()
        ]);
    }

    #[Route('/addTrick', name: 'add_trick')]
    public function addTrick(Request $request, EntityManagerInterface $entityManager, TrickRepository $trickRepository)
    {
        $trick = new Trick();

        $video = new Video();
        $trick->getVideos()->add($video);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $getTrickByName = $trickRepository->findOneBy(['name' => $trick->getName()]);
            if($getTrickByName == null){
                $video->setTrick($trick);
                $author = $this->getUser()->getUsername();
                $trick->setAuthor($author);

                $entityManager->persist($trick);
                $entityManager->flush();
                $this->addFlash('success', 'Votre figure a bien été créée !');
                return $this->redirectToRoute('home');   
            } else {
                $errors[] = 'La figure que vous essayez de créer existe déjà.';
            }
        }    

        return $this->render('pages/addTrick/index.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }

    #[Route('/updateTrick/{id}', name: 'update_trick')]
    public function updateTrick(Request $request, EntityManagerInterface $entityManager, int $id, TrickRepository $trickRepository)
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('show_trick', ['id' => $id]);
        }    

        return $this->render('pages/updateTrick/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/deleteTrick/{id}', name: 'delete_trick')]
    public function deleteTrick(EntityManagerInterface $entityManager, int $id, TrickRepository $trickRepository)
    {
        $trick = $trickRepository->findOneBy(['id' => $id]);

        $entityManager->remove($trick);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}