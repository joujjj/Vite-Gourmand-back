<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ORM\Table(name: 'avis')]
class Avis
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['avis:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['avis:read'])]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['avis:read'])]
    private ?Commande $commande = null;

    #[ORM\Column(type: 'smallint')]
    #[Groups(['avis:read'])]
    private int $note = 5;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['avis:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    #[Groups(['avis:read'])]
    private string $statut = 'en_attente';

    #[ORM\Column]
    #[Groups(['avis:read'])]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $u): static { $this->utilisateur = $u; return $this; }
    public function getCommande(): ?Commande { return $this->commande; }
    public function setCommande(?Commande $c): static { $this->commande = $c; return $this; }
    public function getNote(): int { return $this->note; }
    public function setNote(int $note): static { $this->note = $note; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): static { $this->description = $d; return $this; }
    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $s): static { $this->statut = $s; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
