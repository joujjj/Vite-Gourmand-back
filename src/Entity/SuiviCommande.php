<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'suivi_commande')]
class SuiviCommande
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['commande:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'suivis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    #[ORM\Column(length: 50)]
    #[Groups(['commande:read'])]
    private ?string $statut = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['commande:read'])]
    private ?string $commentaire = null;

    #[ORM\Column]
    #[Groups(['commande:read'])]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getCommande(): ?Commande { return $this->commande; }
    public function setCommande(?Commande $commande): static { $this->commande = $commande; return $this; }
    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }
    public function getCommentaire(): ?string { return $this->commentaire; }
    public function setCommentaire(?string $commentaire): static { $this->commentaire = $commentaire; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
