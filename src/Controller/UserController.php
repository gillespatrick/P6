<?php

namespace App\Controller;

use App\Entity\UpdatePassword;
use App\Entity\User;
use App\Form\PasswordUpdateType;
use App\Form\ProfileType;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * This side of application display the user dashboard.
     *
     * @Route("/user/dashboard", name="show_user")
     * IsGranted("ROLE_USER")
     */
    public function index(UserRepository $repo)
    {
        // Display user dashboard

        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->findAll();

        return $this->render('user/user.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     *This part of the application provides a means for the users to update their information.
     *
     * @Route("/user/update/",name ="update_profile")
     * IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        // Allow to edit my profile.

        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Add the img  and change the name file
            $image = $form->get('img')->getData();
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('upload_images'), $fileName);
            $user->setImg($fileName);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'notice',
                'your changes have been supported'
            );
        }

        return $this->render('user/profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            //'user' => $this ->getUser()
        ]);
    }

    /**
     * This part of the application provides a means for the users to reset their password.
     *
     * @Route("/reset_password", name = "reset_password")
     */
    public function passwordForgot(Request $request, EntityManagerInterface $manager, UserRepository $repo, \Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->getData('username');
            $user = $repo->findOneByUsername($username);
            if (null !== $user) {
                $user->setToken(md5(random_bytes(10)));

                $manager->persist($user);
                $manager->flush();

                // Send email address to user to active them news passwords

                $message = (new \Swift_Message('SnowTricks - Reset password'))
                ->setFrom('noreply@snowtrickmass.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('user/reset.html.twig', [
                            'user' => $user,
                        ]),
                        'text/html'
                    )
                ;

                $mailer->send($message);

                $this->addFlash(
                    'success',
                    'A password reset email was sent to the email linked to your account!'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'This user does not exist!'
                );
            }

            return $this->redirectToRoute('reset_password');
        }

        return $this->render('user/reset_password.html.twig', [
         'form' => $form->createView(),
              ]);
    }

    /**
     * Here the users reset their password if token match.
     *
     * @Route("password_reset/{username}/{token}", name="password_reset")
     */
    public function passwordReset(Request $request, UserRepository $repo, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager, $username, $token)
    {
        $user = $repo->findOneByUsername($username);

        $form = $this->createForm(ResetPasswordFormType::class, $user);

        $form->handleRequest($request);

        //Save the new password

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getToken() === $token) {
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Password successfully changed!'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'Password modification failed! The validation link has expired!'
                );
            }

            return $this->redirectToRoute('login_user');
        }

        return $this->render('user/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This function allow to logout of user account.
     *
     * @Route("/user/logout", name="logout")
     */
    public function logout()
    {
        //nothing here ...
        // logout user
    }

    /**
     * We call this controller to update user password if he's connected.
     *
     * @Route("/user/update_password", name = "update_password")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        // Update password form

        $passwordupdate = new UpdatePassword();
        $user = new User();
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordupdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the old password is the same that password and save
            if (!password_verify($passwordupdate->getOldPassword(), $user->getPassword())) {
                $form->get('oldPassword')->addError(new FormError(" This password isn't actually your password "));
            } else {
                $newPassword = $passwordupdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setPassword($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'notice',
                    'Your password has been changed successfully...'
                );

                return $this->redirectToRoute('show_user');
            }
        }

        return $this->render('user/updatepassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
