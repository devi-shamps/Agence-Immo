<?php

namespace App\Entity;

use App\Repository\BienImmobilierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BienImmobilierRepository::class)]
class BienImmobilier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $rue = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $codePostal = null;

    #[ORM\ManyToOne(inversedBy: 'bienImmobiliers')]
    private ?User $user = null;

    /**
     * @var Collection<int, Annonce>
     */
    #[ORM\OneToMany(mappedBy: 'bienImmobilier', targetEntity: Annonce::class)]
    private Collection $annonces;

    /**
     * @var Collection<int, Piece>
     */
    #[ORM\OneToMany(mappedBy: 'bienImmobilier', targetEntity: Piece::class)]
    private Collection $pieces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->pieces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): static
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setBienImmobilier($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): static
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getBienImmobilier() === $this) {
                $annonce->setBienImmobilier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Piece>
     */
    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function addPiece(Piece $piece): static
    {
        if (!$this->pieces->contains($piece)) {
            $this->pieces->add($piece);
            $piece->setBienImmobilier($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): static
    {
        if ($this->pieces->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getBienImmobilier() === $this) {
                $piece->setBienImmobilier(null);
            }
        }

        return $this;
    }

    public function surfaceHabitable(): float
    {
        $surfaceHabitable = 0.0;

        foreach ($this->pieces as $piece) {
            if ($piece->getTypePiece()->isSurfaceHabitable()) {
                $surfaceHabitable += $piece->getSurface();
            }
        }

        return $surfaceHabitable;
    }

    public function surfaceNonHabitable(): float
    {
        $surfaceNonHabitable = 0.0;

        foreach ($this->pieces as $piece) {
            if (!$piece->getTypePiece()->isSurfaceHabitable()) {
                $surfaceNonHabitable += $piece->getSurface();
            }
        }

        return $surfaceNonHabitable;
    }
}
