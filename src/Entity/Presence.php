<?php

namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresenceRepository::class)]
class Presence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'presences')]
    private ?Membres $membre = null;

    #[ORM\ManyToOne(inversedBy: 'presences')]
    private ?Reunion $reunion = null;

    #[ORM\Column]
    private ?bool $present = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMembre(): ?Membres
    {
        return $this->membre;
    }

    public function setMembre(?Membres $membre): static
    {
        $this->membre = $membre;

        return $this;
    }

    public function getReunion(): ?Reunion
    {
        return $this->reunion;
    }

    public function setReunion(?Reunion $reunion): static
    {
        $this->reunion = $reunion;

        return $this;
    }

    public function isPresent(): ?bool
    {
        return $this->present;
    }

    public function setPresent(bool $present): static
    {
        $this->present = $present;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
