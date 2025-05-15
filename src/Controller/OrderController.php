<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Ville;
use App\Form\OrderType;
use App\Repository\ProduitRepository;
use App\Service\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;


final class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(Request $request, SessionInterface $session, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, Cart $cart): Response
    {
        $cart = $session->get('cart', []);
        $cartWhitData = [];
        foreach($cart as $id => $quantity){
            $cartWhitData[] = [
                'produit' =>$produitRepository->find($id),
                'quantity' => $quantity
            ];
        }
    
        $total = array_sum(array_map(function ($item){
            return $item['produit']->getPrix() * $item['quantity'];
        }, $cartWhitData));

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            if($order->isPayerLivraison()){
                
            }
        }


        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'total' => $total,
        ]);
    }
    // Frais de livraison
    #[Route('/ville/{id}/shipping/cost', name: 'app_ville_shipping_cost')]
    public function villeShippingCost(Ville $ville){
        $vileShippingPrix = $ville->getFraisLivraison();
        return new Response(json_encode(['status' => 200, "message" => 'on', 'content' => $vileShippingPrix]));
    }

    
}
