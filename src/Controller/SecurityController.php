<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * This page allows the user to connect to his account.
     *
     * @Route("/user/login", name="login_user")
     */
    public function login(AuthenticationUtils $auth): Response
    {
        // Open login form

        $error = $auth->getLastAuthenticationError();
        $username = $auth->getLastUsername();

        return $this->render('user/login.html.twig', [
           // 'hasError' => null !== $error,
           'username' => $username,
           'error' => $error,
        ]);
    }
}
