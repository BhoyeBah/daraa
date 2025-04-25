<?php

namespace App\Entity;

use App\Repository\ReunionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReunionRepository::class)]
class Reunion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\ManyToOne(inversedBy: 'reunions')]
    private ?Encadreur $encadreur = null;

    #[ORM\ManyToOne(inversedBy: 'reunions')]
    private ?Themes $theme = null;

    #[ORM\ManyToOne(inversedBy: 'reunions')]
    private ?Dahiras $dahiras = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $sujetaborde = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $decisionprise = null;

    /**
     * @var Collection<int, Presence>
     */
    #[ORM\OneToMany(targetEntity: Presence::class, mappedBy: 'reunion')]
    private Collection $presences;

    /**
     * @var Collection<int, Intervenant>
     */
    #[ORM\OneToMany(targetEntity: Intervenant::class, mappedBy: 'reunion')]
    private Collection $intervenants;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    /**
     * @var Collection<int, Membres>
     */
    #[ORM\ManyToMany(targetEntity: Membres::class, inversedBy: 'reunions')]
    private Collection $membres;

   

    public function __construct()
    {
        $this->presences = new ArrayCollection();
        $this->intervenants = new ArrayCollection();
        $this->membres = new ArrayCollection();
        
    }

    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEncadreur(): ?Encadreur
    {
        return $this->encadreur;
    }

    public function setEncadreur(?Encadreur $encadreur): static
    {
        $this->encadreur = $encadreur;

        return $this;
    }

    public function getTheme(): ?Themes
    {
        return $this->theme;
    }

    public function setTheme(?Themes $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getDahiras(): ?Dahiras
    {
        return $this->dahiras;
    }

    public function setDahiras(?Dahiras $dahiras): static
    {
        $this->dahiras = $dahiras;

        return $this;
    }

    public function getSujetaborde(): ?string
    {
        return $this->sujetaborde;
    }

    public function setSujetaborde(string $sujetaborde): static
    {
        $this->sujetaborde = $sujetaborde;

        return $this;
    }

    public function getDecisionprise(): ?string
    {
        return $this->decisionprise;
    }

    public function setDecisionprise(string $decisionprise): static
    {
        $this->decisionprise = $decisionprise;

        return $this;
    }

    /**
     * @return Collection<int, Presence>
     */
    public function getPresences(): Collection
    {
        return $this->presences;
    }

    public function addPresence(Presence $presence): static
    {
        if (!$this->presences->contains($presence)) {
            $this->presences->add($presence);
            $presence->setReunion($this);
        }

        return $this;
    }

    public function removePresence(Presence $presence): static
    {
        if ($this->presences->removeElement($presence)) {
            // set the owning side to null (unless already changed)
            if ($presence->getReunion() === $this) {
                $presence->setReunion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Intervenant>
     */
    public function getIntervenants(): Collection
    {
        return $this->intervenants;
    }

    public function addIntervenant(Intervenant $intervenant): static
    {
        if (!$this->intervenants->contains($intervenant)) {
            $this->intervenants->add($intervenant);
            $intervenant->setReunion($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenant $intervenant): static
    {
        if ($this->intervenants->removeElement($intervenant)) {
            // set the owning side to null (unless already changed)
            if ($intervenant->getReunion() === $this) {
                $intervenant->setReunion(null);
            }
        }

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * @return Collection<int, Membres>
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Membres $membre): static
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
        }

        return $this;
    }

    public function removeMembre(Membres $membre): static
    {
        $this->membres->removeElement($membre);

        return $this;
    }

    
}
