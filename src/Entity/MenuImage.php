<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'menu_image')]
class MenuImage
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['menu:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menu $menu = null;

    #[ORM\Column(length: 500)]
    #[Groups(['menu:read'])]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['menu:read'])]
    private ?string $alt = null;

    #[ORM\Column]
    #[Groups(['menu:read'])]
    private bool $principale = false;

    public function getId(): ?int { return $this->id; }
    public function getMenu(): ?Menu { return $this->menu; }
    public function setMenu(?Menu $m): static { $this->menu = $m; return $this; }
    public function getUrl(): ?string { return $this->url; }
    public function setUrl(string $url): static { $this->url = $url; return $this; }
    public function getAlt(): ?string { return $this->alt; }
    public function setAlt(?string $alt): static { $this->alt = $alt; return $this; }
    public function isPrincipale(): bool { return $this->principale; }
    public function setPrincipale(bool $p): static { $this->principale = $p; return $this; }
}
