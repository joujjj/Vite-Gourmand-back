<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'plat')]
class Plat
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['menu:read', 'plat:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['menu:read', 'plat:read'])]
    private ?string $typePlat = null;

    #[ORM\Column(length: 255)]
    #[Groups(['menu:read', 'plat:read'])]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['menu:read', 'plat:read'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Allergene::class)]
    #[ORM\JoinTable(name: 'plat_allergene')]
    #[Groups(['menu:read', 'plat:read'])]
    private Collection $allergenes;

    public function __construct()
    {
        $this->allergenes = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTypePlat(): ?string { return $this->typePlat; }
    public function setTypePlat(string $t): static { $this->typePlat = $t; return $this; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $n): static { $this->nom = $n; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): static { $this->description = $d; return $this; }
    public function getAllergenes(): Collection { return $this->allergenes; }
    public function addAllergene(Allergene $a): static { if (!$this->allergenes->contains($a)) $this->allergenes->add($a); return $this; }
    public function removeAllergene(Allergene $a): static { $this->allergenes->removeElement($a); return $this; }
}
