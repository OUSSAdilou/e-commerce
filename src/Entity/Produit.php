<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $prix = null;

    /**
     * @var Collection<int, SubCategory>
     */
    #[ORM\ManyToMany(targetEntity: SubCategory::class, inversedBy: 'produits')]
    private Collection $subCategories;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $stock = null;

    /**
     * @var Collection<int, AjoutProduitHistorique>
     */
    #[ORM\OneToMany(targetEntity: AjoutProduitHistorique::class, mappedBy: 'produit')]
    private Collection $ajoutProduitHistoriques;

    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
        $this->ajoutProduitHistoriques = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, SubCategory>
     */
    public function getSubCategories(): Collection
    {
        return $this->subCategories;
    }

    public function addSubCategory(SubCategory $subCategory): static
    {
        if (!$this->subCategories->contains($subCategory)) {
            $this->subCategories->add($subCategory);
        }

        return $this;
    }

    public function removeSubCategory(SubCategory $subCategory): static
    {
        $this->subCategories->removeElement($subCategory);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, AjoutProduitHistorique>
     */
    public function getAjoutProduitHistoriques(): Collection
    {
        return $this->ajoutProduitHistoriques;
    }

    public function addAjoutProduitHistorique(AjoutProduitHistorique $ajoutProduitHistorique): static
    {
        if (!$this->ajoutProduitHistoriques->contains($ajoutProduitHistorique)) {
            $this->ajoutProduitHistoriques->add($ajoutProduitHistorique);
            $ajoutProduitHistorique->setProduit($this);
        }

        return $this;
    }

    public function removeAjoutProduitHistorique(AjoutProduitHistorique $ajoutProduitHistorique): static
    {
        if ($this->ajoutProduitHistoriques->removeElement($ajoutProduitHistorique)) {
            // set the owning side to null (unless already changed)
            if ($ajoutProduitHistorique->getProduit() === $this) {
                $ajoutProduitHistorique->setProduit(null);
            }
        }

        return $this;
    }

}
