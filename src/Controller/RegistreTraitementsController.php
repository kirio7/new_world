<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegistreTraitementsController extends AbstractController
{
    #[Route('/registre/traitements', name: 'app_registre_traitements')]
    public function index(): Response
    {
        return $this->render('registre_traitements/index.html.twig', [
            'controller_name' => 'RegistreTraitementsController',
        ]);
    }
}
