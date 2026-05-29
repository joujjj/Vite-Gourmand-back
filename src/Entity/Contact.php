<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contact')]
class Contact
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column]
    private bool $traite = false;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $e): static { $this->email = $e; return $this; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $t): static { $this->titre = $t; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $d): static { $this->description = $d; return $this; }
    public function isTraite(): bool { return $this->traite; }
    public function setTraite(bool $t): static { $this->traite = $t; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
