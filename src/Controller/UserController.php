<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users'=>$userRepository->findAll()
        ]);
    }

    #[Route('/admin/user/{id}/to/editor', name: 'app_user_to_editor')]
    public function changeRole(EntityManagerInterface $entityManager, User $user):Response
    {
        $user->setRoles(["ROLE_EDITOR", "ROLE_USER"]);
        $entityManager->flush();

        $this->addFlash('success', 'Le rôle editeur est ajouter à votre utilisateurs');

        return $this->redirectToRoute('app_user');
    }

    #[Route('/admin/user/{id}/remove/editor', name: 'app_user_remove_editor')]
    public function editorRoleRemove (EntityManagerInterface $entityManager, User $user):Response
    {
        $user->setRoles([]);
        $entityManager->flush();

        $this->addFlash('danger', 'Le rôle editeur à été retirer à votre utilisateurs');

        return $this->redirectToRoute('app_user');
    }

    #[Route('/admin/user/{id}/remove', name: 'app_user_remove')]
    public function removeUser(EntityManagerInterface $entityManager, $id, UserRepository $userRepository):Response
    {
        $userFind = $userRepository->find($id);
        $entityManager->remove($userFind);
        $entityManager->flush();

        $this->addFlash('danger', 'Votre utilisateur à été supprimer');

        return $this->redirectToRoute('app_user');
    }
    
}
