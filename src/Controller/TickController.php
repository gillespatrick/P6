<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TickController extends AbstractController
{
    /**
     * Here the user should create a new trick but while logged in.
     *
     * @Route("/trick/addtrick", name="add_trick")
     * @IsGranted("ROLE_USER")
     */

    // Function to add a trick
    public function add(Trick $trick = null, Request $request, EntityManagerInterface $manager)
    {
        $media = new Media();
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($trick->getMedia() as $media) {
                $media->setTrick($trick);
                $manager->persist($media);
            }

            // Add the medias tricks and change the name file
            $image = $form->get('cover')->getData();
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('upload_images'), $fileName);
            $trick->setCover($fileName);

            // Save

            if (!$trick->getId()) {
                $trick->setCreateDate(new \DateTime());
            }

            $trick->setUser($this->getUser());
            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'notice',
                'The trick has been added'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('home/new_trick.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * On this page we have the detail of the trick on which the visitor wishes
     *  to have more information.
     *
     * @Route("/trick/{slug}/{page<\d+>?1}", name = "showdetail")
     *
     * @param mixed $slug
     * @param mixed $page
     */
    public function show($slug, $page, Request $request, EntityManagerInterface $manager, CommentRepository $repo)
    {
        // Diplays a single trick via slug

        $trickRepository = $this->getDoctrine()->getRepository(Trick::class);
        $trick = $trickRepository->findOneBySlug($slug);

        // This comment pagination
        $limit = 4;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);

        // Add Comments
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setTrick($trick);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // We save the comment that match with trick.
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreateDate(new \DateTime());
            //$comment->setTrick($trick);
            // $comment->setUser($this->getUser());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Your comment has been saved !'
            );

            return $this->redirectToRoute('showdetail', [
                'slug' => $trick->getSlug(),
            ]);
        }

        return $this->render('home/showDetail.html.twig', [
            'comments' => $repo->findBy([], ['create_date' => 'DESC'], $limit, $start),
            'pages' => $pages,
            'page' => $page,

            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Here the user should delete a trick but while logged in
     * and also if it's him who create that trick.
     *
     * @Route("/trick/{slug}/delete", name = "trickdelete")
     * @Security("is_granted('ROLE_USER') and user == trick.getUser()", message = "You do not have the rule to access this resource")
     *
     * @param response
     */
    public function delete(Trick $trick, EntityManagerInterface $manager)
    {
        //Allow to delete a trick.

        $manager->remove($trick);
        $manager->flush();

        $this->addFlash(
            'notice',
            "The trick {$trick->getName()} has been deleted !"
        );

        return  $this->redirectToRoute('show_user');
    }

    /**
     *Here the user should edit a trick that created it but while logged in.
     *
     * @Route("/trick/{slug}/edit", name = "trick_edit")
     *
     * @Security("is_granted('ROLE_USER') and user === trick.getUser()")
     *
     * @return Response
     */
    public function edit(Request $request, Trick $trick, EntityManagerInterface $manager)
    {
        // Display the edition form and edit trick

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($trick->getMedia() as $media) {
                $media->setTrick($trick);
                $manager->persist($media);
            }
            // Add the medias tricks and change the name file

            $image = $form->get('cover')->getData();
            $fileName = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('upload_images'), $fileName);
            $trick->setCover($fileName);

            // Save

            if (!$trick->getId()) {
                $trick->setCreateDate(new \DateTime());
            }

            $trick->setUser($this->getUser());
            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'notice',
                'The editions trick have been added'
            );

            return $this->redirectToRoute('show_user');
        }

        return $this->render('home/editTrick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }
}
