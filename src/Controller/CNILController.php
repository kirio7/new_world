<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CNILController extends AbstractController
{
    #[Route('/c/n/i/l', name: 'app_cnil')]
    public function index(): Response
    {
        return $this->render('cnil/index.html.twig', [
            'controller_name' => 'CNILController',
        ]);
    }
}
