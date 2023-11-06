<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fileName = null;

    #[Assert\Image([
        'maxSize' => '15M',
        "mimeTypes" => [
            "image/png",
            "image/jpg",
            "image/jpeg",
        ],
        "mimeTypesMessage" => "Veuillez envoyer une image au format png, jpg ou jpeg, de 15 mégas octets maximum"
    ])]
    private ?File $file = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(onDelete: "CASCADE", nullable: false)]
    private ?Trick $trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile( $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }
}
