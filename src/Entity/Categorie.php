<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $environnement = null;

    /**
     * @var Collection<int, Cactus>
     */
    #[ORM\OneToMany(targetEntity: Cactus::class, mappedBy: 'categorie', orphanRemoval: true)]
    private Collection $cactus;

    public function __construct()
    {
        $this->cactus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEnvironnement(): ?string
    {
        return $this->environnement;
    }

    public function setEnvironnement(string $environnement): static
    {
        $this->environnement = $environnement;

        return $this;
    }

    /**
     * @return Collection<int, Cactus>
     */
    public function getCactus(): Collection
    {
        return $this->cactus;
    }

    public function addCactu(Cactus $cactu): static
    {
        if (!$this->cactus->contains($cactu)) {
            $this->cactus->add($cactu);
            $cactu->setCategorie($this);
        }

        return $this;
    }

    public function removeCactu(Cactus $cactu): static
    {
        if ($this->cactus->removeElement($cactu)) {
            // set the owning side to null (unless already changed)
            if ($cactu->getCategorie() === $this) {
                $cactu->setCategorie(null);
            }
        }

        return $this;
    }
}
