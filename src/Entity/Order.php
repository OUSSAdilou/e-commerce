<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Ville $ville = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $payerLivraison = null;

    /**
     * @var Collection<int, ProduitsCommander>
     */
    #[ORM\OneToMany(targetEntity: ProduitsCommander::class, mappedBy: '_order', orphanRemoval: true)]
    private Collection $produitsCommanders;

    #[ORM\Column]
    private ?float $prixTotal = null;

    public function __construct()
    {
        $this->produitsCommanders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

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

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isPayerLivraison(): ?bool
    {
        return $this->payerLivraison;
    }

    public function setPayerLivraison(bool $payerLivraison): static
    {
        $this->payerLivraison = $payerLivraison;

        return $this;
    }

    /**
     * @return Collection<int, ProduitsCommander>
     */
    public function getProduitsCommanders(): Collection
    {
        return $this->produitsCommanders;
    }

    public function addProduitsCommander(ProduitsCommander $produitsCommander): static
    {
        if (!$this->produitsCommanders->contains($produitsCommander)) {
            $this->produitsCommanders->add($produitsCommander);
            $produitsCommander->setOrder($this);
        }

        return $this;
    }

    public function removeProduitsCommander(ProduitsCommander $produitsCommander): static
    {
        if ($this->produitsCommanders->removeElement($produitsCommander)) {
            // set the owning side to null (unless already changed)
            if ($produitsCommander->getOrder() === $this) {
                $produitsCommander->setOrder(null);
            }
        }

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
}
