<?php
// src/Controller/AdminDashboardController.php
namespace App\Controller;

use App\Model\BDD;
use App\Repository\ProducteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
if (!isset($_SESSION['maintenance_done'])) {
    $_SESSION['maintenance_done'] = date('Y-m-d');
    BDD::maintenanceProducteurs();
}
class AdminDashboardArchiveController extends AbstractController
{
    #[Route('/archive', name: 'admin_contrats_termines')]
    public function index(
        ProducteurRepository $producteursRepo,
    ): Response {
        $producteurs = $producteursRepo->findAll();

        return $this->render('admin_dashboard_archive/index.html.twig', [
            'controller_name' => 'AdminDashboardArchiveController',
            'producteurs' => $producteurs,
        ]);
    }
}