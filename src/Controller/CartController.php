<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Service\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{

    public function __construct(private readonly ProduitRepository $produitRepository){}
    // Gestion de panier
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, Cart $cart): Response
    {
       
        $data = $cart->getCart($session);
        // dd($cartWhitData);
        // dd($total);
        return $this->render('cart/index.html.twig', [
            'items'=>$data['cart'],
            'total' => $data['total'],
        ]);
    }

    // Supprimer un élément du panier
    #[Route('/cart/add/{id}', name: 'app_cart_new')]
    public function addToCart($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        if(!empty($cart[$id]))
        {
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_produit_remove')]
    public function remove($id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        if(!empty($cart[$id])){
            unset($cart[$id]);
        }
        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    // Vider le panier
    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function removeAll(SessionInterface $session): Response
    {
        $session ->set('cart', []);

        return $this->redirectToRoute('app_cart');
    }

}
