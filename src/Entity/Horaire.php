<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: 'horaire')]
class Horaire
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['horaire:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'smallint')]
    #[Groups(['horaire:read'])]
    private int $jour = 1;

    #[ORM\Column(length: 5, nullable: true)]
    #[Groups(['horaire:read'])]
    private ?string $heureOuverture = null;

    #[ORM\Column(length: 5, nullable: true)]
    #[Groups(['horaire:read'])]
    private ?string $heureFermeture = null;

    #[ORM\Column(length: 10)]
    #[Groups(['horaire:read'])]
    private string $service = 'midi';

    #[ORM\Column]
    #[Groups(['horaire:read'])]
    private bool $ferme = false;

    public function getId(): ?int { return $this->id; }
    public function getJour(): int { return $this->jour; }
    public function setJour(int $j): static { $this->jour = $j; return $this; }
    public function getHeureOuverture(): ?string { return $this->heureOuverture; }
    public function setHeureOuverture(?string $h): static { $this->heureOuverture = $h; return $this; }
    public function getHeureFermeture(): ?string { return $this->heureFermeture; }
    public function setHeureFermeture(?string $h): static { $this->heureFermeture = $h; return $this; }
    public function getService(): string { return $this->service; }
    public function setService(string $s): static { $this->service = $s; return $this; }
    public function isFerme(): bool { return $this->ferme; }
    public function setFerme(bool $f): static { $this->ferme = $f; return $this; }
}
