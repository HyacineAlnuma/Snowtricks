<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;

class FileUploader
{
    public function __construct(private SluggerInterface $slugger) {
    }

    public function upload(Form $images, string $targetDirectory): void
    {
        foreach($images as $image) {
            dd($image);
            $file = $image->get('file')->getData();
            if(!$file) {
                continue;
            }
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = uniqid().'.'.$file->guessExtension();

            try {
                $file->move($targetDirectory, $fileName);
                $image->getData()->setFileName($fileName);
            } catch (FileException $e) {
                //exception si erreur pendant l'upload
            }
        }
    }
}