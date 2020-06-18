<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    

    /**
     * Home page with numbered pagination.
     *
     * @Route("/{page<\d+>?1}", name="home")
     */
    public function pagination(TrickRepository $repo, $page)
    {
        // Setting up pagination on the home page.

        $limit = 6;
        $start = $page * $limit - $limit;
        $all = count($repo->findAll());
        $pages = ceil($all / $limit);

        return $this->render('home/home.html.twig', [
            'tricks' => $repo->findBy([], ['create_date' => 'DESC'], $limit, $start),
            'pages' => $pages,
            'page' => $page,
        ]);
    }
}
