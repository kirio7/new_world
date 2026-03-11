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
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // on récupère seulement les produits de ce producteur
        $produits = BDD::produitsByProducteur($user->getSiret());

        foreach ($produits as $produit) {
            $pct = $produit->getPourcentage();
            $pct = max(0, min($pct*100, 100));
            $produit->setPourcentage($pct);
        }

        return $this->render('producteur_dashboard/index.html.twig', [
            'produits' => $produits,
            'producteur' => $user,
        ]);
    }
}