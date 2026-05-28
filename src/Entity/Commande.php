<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateCree = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?float $prixTotal = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'commande')]
    private ?User $acheteur = null;

    /**
     * @var Collection<int, ContenuCommande>
     */
    #[ORM\OneToMany(targetEntity: ContenuCommande::class, mappedBy: 'commande')]
    private Collection $contenuCommandes;

    public function __construct()
    {
        $this->contenuCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCree(): ?\DateTime
    {
        return $this->dateCree;
    }

    public function setDateCree(\DateTime $dateCree): static
    {
        $this->dateCree = $dateCree;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getAcheteur(): ?User
    {
        return $this->acheteur;
    }

    public function setAcheteur(?User $acheteur): static
    {
        $this->acheteur = $acheteur;

        return $this;
    }

    /**
     * @return Collection<int, ContenuCommande>
     */
    public function getContenuCommandes(): Collection
    {
        return $this->contenuCommandes;
    }

    public function addContenuCommande(ContenuCommande $contenuCommande): static
    {
        if (!$this->contenuCommandes->contains($contenuCommande)) {
            $this->contenuCommandes->add($contenuCommande);
            $contenuCommande->setCommande($this);
        }

        return $this;
    }

    public function removeContenuCommande(ContenuCommande $contenuCommande): static
    {
        if ($this->contenuCommandes->removeElement($contenuCommande)) {
            // set the owning side to null (unless already changed)
            if ($contenuCommande->getCommande() === $this) {
                $contenuCommande->setCommande(null);
            }
        }

        return $this;
    }
}
