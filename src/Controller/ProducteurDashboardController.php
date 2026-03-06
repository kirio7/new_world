<?php
// src/Controller/ProducteurDashboardController.php
namespace App\Controller;

use App\Entity\Produit;
use App\Model\BDD;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProducteurDashboardController extends AbstractController
{
    #[Route('/producteur/dashboard', name: 'producteur_dashboard')]
    public function index(Produit $produits): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // on récupère seulement les produits de ce producteur
        $produits = BDD::produit();

        foreach ($produits as $produit) {
            $pct = round($produit->getQuantite());
            $pct = max(0, min($pct, 100));
            $produit->qtyPct = $pct; // propriété dynamique
        }

        // on récupère seulement les produits de ce producteur
        $produits = $produits->findBy(['producteur' => $user->getSiret()]);


        return $this->render('producteur/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}