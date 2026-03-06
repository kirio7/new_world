<?php

namespace App\Controller;

use App\Model\BDD;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil_controller')]
    public function index(): Response
    {
        if (!isset($_SESSION['maintenance_done'])) {
            $_SESSION['maintenance_done'] = date('Y-m-d');
            BDD::maintenanceProducteurs();
        }
        
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
}
