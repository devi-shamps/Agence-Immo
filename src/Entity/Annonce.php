<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prixM2Habitable = null;

    private const COEF_NON_HABITABLE = '0.7';

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?BienImmobilier $bienImmobilier = null;

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrixM2Habitable(): ?string
    {
        return $this->prixM2Habitable;
    }

    public function setPrixM2Habitable(string $prixM2Habitable): static
    {
        $this->prixM2Habitable = $prixM2Habitable;

        return $this;
    }

    public function getBienImmobilier(): ?BienImmobilier
    {
        return $this->bienImmobilier;
    }

    public function setBienImmobilier(?BienImmobilier $bienImmobilier): static
    {
        $this->bienImmobilier = $bienImmobilier;

        return $this;
    }

    public function prix(): float
    {
        $prixM2Habitable = $this->getPrixM2Habitable();
        $bienImmobilier = $this->getBienImmobilier();

        $surfaceHabitable = $bienImmobilier->surfaceHabitable();
        $surfaceNonHabitable = $bienImmobilier->surfaceNonHabitable();

        $prixTotal = $prixM2Habitable * $surfaceHabitable;
        $prixTotal += $prixM2Habitable * self::COEF_NON_HABITABLE * $surfaceNonHabitable;

        return $prixTotal;
    }
}
