<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(private SluggerInterface $slugger) {
    }

    public function upload(mixed $images, string $targetDirectory)
    {
        if (get_class($images) == "Symfony\Component\HttpFoundation\File\UploadedFile") {
            return $this->uploadSingle($images, $targetDirectory);
        } elseif (get_class($images) == "Symfony\Component\Form\Form") {
            $this->uploadMultiple($images, $targetDirectory);
        } else {
            return null;
        }
    }

    public function uploadMultiple(Form $images, string $targetDirectory): void
    {
        foreach($images as $image) {
            $file = $image->get('file')->getData();
            if(!$file) {
                continue;
            }
            $fileName = $this->uploadSingle($file, $targetDirectory);
            $image->getData()->setFileName($fileName);
        }
    }

    public function uploadSingle(UploadedFile $file, string $targetDirectory): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($targetDirectory, $fileName);
        } catch (FileException $e) {
            //exception si erreur pendant l'upload
        }

        return $fileName;
    }
}