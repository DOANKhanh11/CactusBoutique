<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?\DateTime $dateCree = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteur = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $vendeur = null;

    public function __construct()
    {
        $this->dateCree = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): static { $this->contenu = $contenu; return $this; }

    public function getDateCree(): ?\DateTime { return $this->dateCree; }
    public function setDateCree(\DateTime $dateCree): static { $this->dateCree = $dateCree; return $this; }

    public function getAuteur(): ?User { return $this->auteur; }
    public function setAuteur(User $auteur): static { $this->auteur = $auteur; return $this; }

    public function getVendeur(): ?User { return $this->vendeur; }
    public function setVendeur(User $vendeur): static { $this->vendeur = $vendeur; return $this; }
}
