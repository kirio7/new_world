<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;
use App\Repository\ProducteurRepository;
use model\BDD;
use App\Entity\Producteur;
use App\Entity\Nutriscore;

final class AdminDashboardProduitController extends AbstractController
{
    #[Route('/admin_produits', name: 'admin_produits')]
    public function index(
        ProduitRepository $produitsRepo,
        ProducteurRepository $producteursRepo
    ): Response {
        $produits = $produitsRepo->findAll();
        $producteurs = $producteursRepo->findAll();

        // Vérifie si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $description = $_POST['description'] ?? '';
            $prix = $_POST['prix'] ?? 0;
            $quantite = $_POST['quantite'] ?? 0;
            $est_bio = isset($_POST['est_bio']) ? true : false;

            // Ici tu peux choisir le producteur et nutriscore
            $producteur = BDD::Producteur()[0]; // exemple : premier producteur
            $nutriscore = null; // ou BDD::Nutriscore()[0]

            // Gestion image upload
            $image = null;
            if (!empty($_FILES['image']['name'])) {
                $image = basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/produits/' . $image);
            }

            $res = BDD::ajouterProduit($nom, $description, $prix, $image, $producteur, 100, $quantite, $nutriscore, '', $est_bio);

            if ($res === 1) {
                echo "<div style='color:green'>Produit ajouté avec succès !</div>";
            } else {
                echo "<div style='color:red'>Erreur lors de l'ajout du produit.</div>";
            }
        }

        foreach ($produits as $produit) {
            $produit->qtyPct = max(0, min(round($produit->getQuantite()), 100));
        }
        return $this->render('admin_dashboard_produit/index.html.twig', [
            'controller_name' => 'AdminDashboardProduitController',
            'produits' => $produits,
            'producteurs' => $producteurs
        ]);
    }
}
