<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function addUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher){

        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted()) {
            if($userForm->isValid()) {
                $this->addFlash(
                    'success',
                    'Bienvenue ' . $user->getFirstname() . ' sur la plateforme'
                );

                $user->setRoles(['ROLE_ADMIN']);

                /* encryptage de mot de passe*/
                $plainPswd = $userForm->get('password')->getData();
                $hashedPswd = $userPasswordHasher->hashPassword($user, $plainPswd);
                $user->setPassword($hashedPswd);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_login');

            } }

        /* où mettre les else, car mon return n'est pas accessible après */

        return $this->render('security/inscription.html.twig', [
            'userForm'=>$userForm->createView()
        ]);


    }
}
