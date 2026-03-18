<?php
// src/Controller/SIDashboardController.php
namespace App\Controller;

use App\Entity\Admin;
use App\Model\BDD;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

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

    #[Route('/SI/{id}/edit/form', name: 'si_edit_user_form', methods: ['GET'])]
    public function getEditForm(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(Admin::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        return new Response($this->renderView('admin/partials/edit_user.html.twig'));
    }

    // Route pour traiter la soumission (POST)
    #[Route('/SI/{id}/edit', name: 'si_edit_admin', methods: ['POST'])]
    public function editerAdmin(Request $request, int $id, EntityManagerInterface $em): Response
{
    $user = $em->getRepository(Admin::class)->find($id);
    if (!$user) {
        return $this->json(['success' => false, 'error' => 'Utilisateur non trouvé'], 404);
    }

    // Récupérer les données
    $email = $request->request->get('email');
    $nom = $request->request->get('nom');
    $prenom = $request->request->get('prenom');
    $roles = $request->request->all('roles') ?: ['ROLE_USER'];

    // Mettre à jour
    $user->setEmail($email);
    $user->setNom($nom);
    $user->setPrenom($prenom);
    $user->setRoles($roles);
    
    $em->flush();

    return $this->json(['success' => true, 'message' => 'OK']);
}
}