<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    public function __construct(private readonly ProduitRepository $produitRepository){}
    public function getCart(SessionInterface $session): array
    {
        $cart = $session->get('cart', []);
        $cartWhitData = [];
        foreach($cart as $id => $quantity){
            $cartWhitData[] = [
                'produit' => $this->produitRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = array_sum(array_map(function ($item){
            return $item['produit']->getPrix() * $item['quantity'];
        }, $cartWhitData));
        return [
            'cart' => $cartWhitData,
            'total' => $total,
        ];
    }
}