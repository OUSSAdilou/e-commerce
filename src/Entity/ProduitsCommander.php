<?php

namespace App\Entity;

use App\Repository\ProduitsCommanderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsCommanderRepository::class)]
class ProduitsCommander
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'produitsCommanders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $_order = null;

    #[ORM\ManyToOne(inversedBy: 'produitsCommanders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column]
    private ?int $qte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->_order;
    }

    public function setOrder(?Order $_order): static
    {
        $this->_order = $_order;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;

        return $this;
    }
}
