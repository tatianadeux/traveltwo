<?php


namespace App\Controller\Admin;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users_list")
     */
    public function listUserAdmin(UserRepository $userRepository)
    {
        /* utilisation de l'autowire de Symfony pour instancier une classe */

        $users = $userRepository->findAll();
        /* utilisation d'une méthode prise dans l'Article Repository*/

        return $this->render('admin/list/users.html.twig', [
            'users' => $users
        ]);
    }
}