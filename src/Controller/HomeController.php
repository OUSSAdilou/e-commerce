<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CategoryRepository;
use App\Repository\ProduitRepository;
use App\Repository\SubCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(ProduitRepository $produitRepository, CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {
        $data = $produitRepository-> findBy([],['id'=>"DESC"]);
        $produits = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('home/index.html.twig', [
            // 'produits' => $produitRepository->findBy([], ['id' => "DESC"]),
            'produits' => $produits,
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/home/produit/{id}/show', name: 'app_home_produit_show', methods: ['GET', 'POST'])]
    public function show(Produit $produit, ProduitRepository $produitRepository, CategoryRepository $categoryRepository): Response
    {
        $lastProduits = $produitRepository->findBy([], ['id' => "DESC"], limit:5);
        return $this->render('home/show.html.twig', [
            'produit' =>$produit,
            'produits' => $lastProduits,
            'categories' => $categoryRepository->findAll(),

        ]);
    }
    #[Route('/home/produit/subcategory/{id}/filtre', name: 'app_home_produit_filtre', methods: ['GET', 'POST'])]
    public function filtre($id, SubCategoryRepository $subCategoryRepository, CategoryRepository $categoryRepository): Response
    {
        $produits = $subCategoryRepository->find($id)->getProduits();
        $subCategory = $subCategoryRepository->find($id);
        return $this->render('home/filtre.html.twig', [
            'produits' => $produits,
            'subCategory' => $subCategory,
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
