<?php

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPageController extends AbstractController
{
    /**
     * @Route("/", name="site_homepage")
     */
    public function homepage(): Response
    {
        return $this->render('site/homepage.html.twig');
    }

    /**
     * @Route("/menu", name="site_menu")
     */
    public function menu(ProductCategoryRepository $categoryRepository): Response
    {
        return $this->render('site/menu.html.twig', [
            'product_categories' => $categoryRepository->findHavingProducts(),
        ]);
    }

    /**
     * @Route("/praca", name="site_work")
     */
    public function work(): Response
    {
        return $this->render('site/work.html.twig');
    }

    /**
     * @Route("/lokalizacja", name="site_localisation")
     */
    public function localisation(): Response
    {
        return $this->render('site/localisation.html.twig');
    }
}
