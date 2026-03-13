<?php
// src/Controller/SIDashboardController.php
namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Repository\ProduitsRepository;
use App\Repository\ProducteursRepository;
use App\Model\BDD;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
if (!isset($_SESSION['maintenance_done'])) {
    $_SESSION['maintenance_done'] = date('Y-m-d');
    BDD::maintenanceProducteurs();
}
class SIDashboardController extends AbstractController
{
    #[Route('/SI', name: 'si_dashboard')]
    public function index(
    ): Response {
        $admin = BDD::Admin();
        return $this->render('si_dashboard/index.html.twig', [
            'utilisateurs' => $admin
        ]);

    }
}