<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Doctrine\Common\Collections\ArrayCollection;

class FileUploader
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(ArrayCollection $images, string $targetDirectory): void
    {
        foreach($images as $image) {
            dump($image);
            // $file = new File($image->getName());
            $fileName = uniqid().'.'.$file->guessExtension();
            $image->setFileName($fileName);

            try {
                $image->move($targetDirectory, $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
        }
    }
}