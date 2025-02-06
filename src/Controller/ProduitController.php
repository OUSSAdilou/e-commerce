<?php

namespace App\Controller;

use App\Entity\AjoutProduitHistorique;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\AddProduitHistoriqueType;
use App\Form\AjoutProduitHistoriqueType;
use App\Form\ProduitUpdateType;
use App\Repository\AjoutProduitHistoriqueRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/editor/produit')]
final class ProduitController extends AbstractController
{
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image){
                $originalName = pathinfo($image-> getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $sluggerInterface->slug($originalName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_dir'),
                        $newFileName
                    );
                }catch (FileException $exception){}

                $produit->setImage($newFileName);
            }
            $entityManager->persist($produit);
            $entityManager->flush();

            $stockHistorique = new AjoutProduitHistorique();
            $stockHistorique->setQte($produit->getStock());
            $stockHistorique->setProduit($produit);
            $stockHistorique->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($stockHistorique);
            $entityManager->flush();


            $this->addFlash('success', 'Votre produit à été ajouter.');
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, SluggerInterface $sluggerInterface): Response
    {
        $form = $this->createForm(ProduitUpdateType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            if ($image){
                $originalName = pathinfo($image-> getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $sluggerInterface->slug($originalName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_dir'),
                        $newFileName
                    );
                }catch (FileException $exception){}

                $produit->setImage($newFileName);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Votre produit à été modifié.');

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);

            $this->addFlash('danger', 'Votre produit à été suoorimé.');

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add/produit/{id}/stock', name: 'app_produit_stock_add', methods: ['GET', 'POST'])]
    public function addStock($id, EntityManagerInterface $entityManager, Request $request, ProduitRepository $produitRepository): Response {
        $addStock = new AjoutProduitHistorique();
        $form = $this->createForm(AjoutProduitHistoriqueType::class,$addStock);
        $form->handleRequest($request);

        $produit = $produitRepository->find($id);

        if($form->isSubmitted() && $form->isValid()){
            if ($addStock->getQte()>0){
                $newQte = $produit->getStock() + $addStock->getQte();
                $produit->setStock($newQte);

                $addStock->setCreatedAt(new \DateTimeImmutable());
                $addStock->setProduit($produit);
                $entityManager->persist($addStock);
                $entityManager->flush();
                
                $this->addFlash('success', 'Votre stock à été mise à jour.');
                return $this->redirectToRoute("app_produit_index");
            }else{
                $this->addFlash('danger', "Le stock ne doit pas être inferieur à 0.");
                return $this->redirectToRoute("app_produit_stock_add", ['id' =>$produit->getId()]);
            }
        }
            

        return $this->render('produit/addStock.html.twig',
        [
            'form' => $form->createView(),
            'produit' => $entityManager->getRepository(Produit::class)->find($id),
        ]);
    }

    #[Route('/add/produit/{id}/stock/historique', name: 'app_produit_stock_add_historique', methods: ['GET', 'POST'])]
    public function produitAjoutHistorique($id, ProduitRepository $produitRepository, AjoutProduitHistoriqueRepository $ajoutProduitHistoriqueRepository):Response
    {
        $produit = $produitRepository->find($id);
        $produitAjoutHistorique = $ajoutProduitHistoriqueRepository->findBy(['produit' => $produit], ['id' =>'DESC']);
        return $this->render('produit/addedStockHistoriqueChow.html.twig', [
            'produitsAjouter' => $produitAjoutHistorique
        ]);

    }


}
