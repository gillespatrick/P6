<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserAccountController extends AbstractController
{
    /**
     * This controller  allow to display the form page to  visitors to create a account.
     *
     * @Route("/register", name = "user_account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        //Display register form.

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash)
                ->setActivate(0)
                ->setToken(md5(random_bytes(10)));

            // Add the img  and change the name file
            $image = $form->get('img')->getData();
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('upload_images'), $fileName);
            $user->setImg($fileName);

            $manager->persist($user);
            $manager->flush();

            // Send email address to visitors to active them accounts

            $message = (new \Swift_Message('Validating your SnowTricks account'))
                ->setFrom('noreply@snowtrickmass.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('user_account/validation.html.twig', [
                        'user' => $user,
                    ]),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash(
                'notice',
                'To complete the process of creating your account,
                 please activate it with the link send in your email. Thanks...'
            );

            return $this->redirectToRoute('login_user');
        }

        return $this->render('user_account/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *This controller  allow to display the form page to  change the profil user.
     *
     * @Route("/profile", name = " user_profile ")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        // Allow to update my profile to update my informations.

        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'notice',
                'your changes have been supported'
            );
        }

        return $this->render('user_account/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * After registration, this is the link that allow to activate accounts user.
     *
     * @Route("/validation/{username}/{token}", name="email_validation")
     */
    public function emailValidation(UserRepository $repo, $username, $token, Request $request, EntityManagerInterface $manager, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator)
    {
        //Email Activation.

        $user = $repo->findOneByUsername($username);
        if ($token === $user->getToken()) {
            $user->setActivate(1);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your account has been successfully activated! You can now log in!'
            );

            return $this->redirectToRoute('login_user');
        } else {
            $this->addFlash(
                'danger',
                'The validation of your account has failed. The validation link has expired!'
            );
        }

        return $this->redirectToRoute('login_user');
    }
}
