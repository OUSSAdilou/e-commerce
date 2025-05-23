<?php

namespace App\Controller;

use App\Form\CategoryFormType;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    // Liste des cayégories
    #[Route('/admin/category', name: 'app_category')]
    
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        // dd($categories);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // Créer une catégorie
    #[Route('/admin/category/new', name: 'app_category_new')]
    public function addCategory(EntityManagerInterface $entityManager, Request $request): Response
    {
        $category = new Category();
        
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Votre categorie à été ajoutée avec succès.');
            return $this->redirectToRoute('app_category');

        }
        return$this->render('category/new.html.twig', ['form' => $form->createView()]);
    }

    // Modifier un catégorie
    #[Route('/admin/category/{id}/update', name: 'app_category_update')]
    public function update(Category $category, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre categorie à été modifiée avec succès.');
            return $this->redirectToRoute('app_category');

        }

        return $this->render('category/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Supprimer une catégorie
    #[Route('/admin/category/{id}/delete', name: 'app_category_delete')]
    public function delete(Category $category, EntityManagerInterface $entityManager, Request $request): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('danger', 'Votre categorie à été supprimée avec succès.');

        return $this->redirectToRoute('app_category');

    }
}
