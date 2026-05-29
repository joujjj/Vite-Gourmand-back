<?php namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

// ─────────────────────────────────────────
// THEME
// ─────────────────────────────────────────
#[ORM\Entity]
#[ORM\Table(name: 'theme')]
class Theme
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['menu:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['menu:read'])]
    private ?string $libelle = null;

    public function getId(): ?int { return $this->id; }
    public function getLibelle(): ?string { return $this->libelle; }
    public function setLibelle(string $l): static { $this->libelle = $l; return $this; }
}
