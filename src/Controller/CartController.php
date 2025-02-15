<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{

    public function __construct(private readonly ProduitRepository $produitRepository){}

    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $cartWhitData = [];
        foreach ($cart as $id => $quantity){
            $cartWhitData[] = [
                'produit' => $this->produitRepository->find($id),
                'quantity' => $quantity
            ];
        }
        $total = array_sum(array_map(function ($item){
            return $item['produit']->getPrix() * $item['quantity'];
        }, $cartWhitData));
        // dd($total);
        return $this->render('cart/index.html.twig', [
            'items' => $cartWhitData,
            'total' => $total,
            
        ]);
    }

    #[Route('/cart/add/{id}/', name: 'app_cart_new', methods: ['GET'])]
    public function addToCart($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }
}
