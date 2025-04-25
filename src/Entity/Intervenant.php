<?php

namespace App\Entity;

use App\Repository\IntervenantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntervenantRepository::class)]
class Intervenant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'intervenants')]
    private ?Membres $membre = null;

    #[ORM\ManyToOne(inversedBy: 'intervenants')]
    private ?Reunion $reunion = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\ManyToOne(inversedBy: 'intervenants')]
    private ?Dahiras $dahira = null;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDahira(): ?Dahiras
    {
        return $this->dahira;
    }

    public function setDahira(?Dahiras $dahira): static
    {
        $this->dahira = $dahira;

        return $this;
    }
}
