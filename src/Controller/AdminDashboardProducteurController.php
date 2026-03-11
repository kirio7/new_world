<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Model\BDD;
use Symfony\Component\HttpFoundation\Request;

final class AdminDashboardProducteurController extends AbstractController
{
    #[Route('/admin_producteurs', name: 'admin_producteurs')]
    public function index(
        Request $request
    ): Response {
        $producteurs = BDD::Producteur();

        $nouveau_producteur = BDD::ajouterProducteur();

        $modeAjout = $request->query->getBoolean('mode_ajout', false);

        return $this->render('admin_dashboard_producteur/index.html.twig', [
            'controller_name' => 'AdminDashboardProducteurController',
            'producteurs' => $producteurs,
            'nouveau_producteur' => $nouveau_producteur,
            'mode_ajout' => $modeAjout
        ]);
    }
}
