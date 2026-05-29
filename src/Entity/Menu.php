<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\Table(name: 'menu')]
class Menu
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['menu:read', 'commande:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['menu:read', 'commande:read'])]
    private ?string $titre = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups(['menu:read'])]
    private int $nombrePersonneMinimum = 1;

    #[ORM\Column]
    #[Assert\Positive]
    #[Groups(['menu:read'])]
    private float $prixParPersonne = 0.0;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['menu:read'])]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['menu:read'])]
    private ?string $conditions = null;

    #[ORM\Column]
    #[Groups(['menu:read'])]
    private int $quantiteRestante = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'theme_id', nullable: true)]
    #[Groups(['menu:read'])]
    private ?Theme $theme = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'regime_id', nullable: true)]
    #[Groups(['menu:read'])]
    private ?Regime $regime = null;

    #[ORM\Column]
    #[Groups(['menu:read'])]
    private bool $actif = true;

    #[ORM\ManyToMany(targetEntity: Plat::class)]
    #[ORM\JoinTable(name: 'menu_plat')]
    #[Groups(['menu:read'])]
    private Collection $plats;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuImage::class, cascade: ['persist', 'remove'])]
    #[Groups(['menu:read'])]
    private Collection $images;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->plats     = new ArrayCollection();
        $this->images    = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    // Calcul remise 10% si nb personnes >= min + 5
    public function calculerPrix(int $nbPersonnes): array
    {
        $seuilRemise = $this->nombrePersonneMinimum + 5;
        $remise      = $nbPersonnes >= $seuilRemise ? 0.10 : 0;
        $prixUnit    = $this->prixParPersonne * (1 - $remise);
        $prixTotal   = $prixUnit * $nbPersonnes;

        return [
            'prix_unitaire'  => round($prixUnit, 2),
            'remise_pct'     => $remise * 100,
            'prix_total'     => round($prixTotal, 2),
            'seuil_remise'   => $seuilRemise,
        ];
    }

    // Getters & Setters
    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $t): static { $this->titre = $t; return $this; }
    public function getNombrePersonneMinimum(): int { return $this->nombrePersonneMinimum; }
    public function setNombrePersonneMinimum(int $n): static { $this->nombrePersonneMinimum = $n; return $this; }
    public function getPrixParPersonne(): float { return $this->prixParPersonne; }
    public function setPrixParPersonne(float $p): static { $this->prixParPersonne = $p; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): static { $this->description = $d; return $this; }
    public function getConditions(): ?string { return $this->conditions; }
    public function setConditions(?string $c): static { $this->conditions = $c; return $this; }
    public function getQuantiteRestante(): int { return $this->quantiteRestante; }
    public function setQuantiteRestante(int $q): static { $this->quantiteRestante = $q; return $this; }
    public function getTheme(): ?Theme { return $this->theme; }
    public function setTheme(?Theme $t): static { $this->theme = $t; return $this; }
    public function getRegime(): ?Regime { return $this->regime; }
    public function setRegime(?Regime $r): static { $this->regime = $r; return $this; }
    public function isActif(): bool { return $this->actif; }
    public function setActif(bool $a): static { $this->actif = $a; return $this; }
    public function getPlats(): Collection { return $this->plats; }
    public function addPlat(Plat $p): static { if (!$this->plats->contains($p)) $this->plats->add($p); return $this; }
    public function removePlat(Plat $p): static { $this->plats->removeElement($p); return $this; }
    public function getImages(): Collection { return $this->images; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
