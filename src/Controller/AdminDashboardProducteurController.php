<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProducteurRepository;
use model\BDD;

final class AdminDashboardProducteurController extends AbstractController
{
    #[Route('/admin_producteurs', name: 'admin_producteurs')]
    public function index(
        ProducteurRepository $producteursRepo,
    ): Response {
        $producteurs = $producteursRepo->findAll();

        // $res = BDD::Producteur(); // selon ta classe BDD

        // if ($res === 1) {
        //     echo "<div style='color:green'>Produit ajouté avec succès !</div>";
        // } else {
        //     echo "<div style='color:red'>Erreur lors de l'ajout du produit.</div>";
        // }

        return $this->render('admin_dashboard_producteur/index.html.twig', [
            'controller_name' => 'AdminDashboardProducteurController',
            'producteurs' => $producteurs,
        ]);
    }
}
