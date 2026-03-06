<?php

namespace App\Controller;

use Model\BDD;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductPageController extends AbstractController
{
    #[Route('/product_page', name: 'app_product_page')]
    public function index(): Response
    {
        return $this->render('product_page/index.html.twig', [
            'controller_name' => 'ProductPageController',
        ]);
    }
}
