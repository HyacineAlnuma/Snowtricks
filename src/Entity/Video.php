<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $videoEmbed = null;

    #[ORM\ManyToOne(inversedBy: 'videos')]
    #[ORM\JoinColumn(onDelete: "CASCADE", nullable: false)]
    private ?Trick $Trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoEmbed(): ?string
    {
        return $this->videoEmbed;
    }

    public function setVideoEmbed(string $videoEmbed): static
    {
        $this->videoEmbed = $videoEmbed;

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
