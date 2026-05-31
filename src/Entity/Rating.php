<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_rating', columns: ['rater_id', 'vendeur_id'])]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $rater = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $vendeur = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getScore(): ?int { return $this->score; }
    public function setScore(int $score): static
    {
        if ($score < 1 || $score > 5) {
            throw new \InvalidArgumentException('Score must be between 1 and 5.');
        }
        $this->score = $score;
        return $this;
    }

    public function getRater(): ?User { return $this->rater; }
    public function setRater(User $rater): static { $this->rater = $rater; return $this; }

    public function getVendeur(): ?User { return $this->vendeur; }
    public function setVendeur(User $vendeur): static { $this->vendeur = $vendeur; return $this; }

    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
}
