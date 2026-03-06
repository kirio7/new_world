<?php
// src/Controller/AdminDashboardController.php
namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Producteur;
use App\Model\BDD;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
if (!isset($_SESSION['maintenance_done'])) {
    $_SESSION['maintenance_done'] = date('Y-m-d');
    BDD::maintenanceProducteurs();
}
class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $produits = BDD::produit();
        $producteurs = BDD::producteur();

        foreach ($produits as $produit) {
            $produit->qtyPct = max(0, min(round($produit->getQuantite()), 100));
        }

        return $this->render('admin_dashboard/index.html.twig', [
            'produits' => $produits,
            'producteurs' => $producteurs,
        ]);
    }
}