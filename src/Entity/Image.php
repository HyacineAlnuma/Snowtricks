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
    private ?string $FileName = null;

    #[Assert\Image([
        'maxSize' => '10M',
        "mimeTypes" => [
            "image/png",
            "image/jpg",
            "image/jpeg",
        ],
        "mimeTypesMessage" => "Veuillez envoyer une image au format png, jpg ou jpeg, de 10 mégas octets maximum"
    ])]
    #[Assert\NotBlank]
    private ?File $file = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(onDelete: "CASCADE", nullable: false)]
    private ?Trick $Trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->FileName;
    }

    public function setFileName(?string $FileName): static
    {
        $this->FileName = $FileName;

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
        return $this->Trick;
    }

    public function setTrick(?Trick $Trick): static
    {
        $this->Trick = $Trick;

        return $this;
    }
}
