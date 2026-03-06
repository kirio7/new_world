<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Producteur;
use App\Form\UserRegistrationType;
use App\Form\AdminRegistrationType;
use App\Form\ProducteurRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Model\BDD;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        // 1️⃣ Choix du type d'utilisateur (par un paramètre GET ou POST)
        $userType = $request->query->get('type', 'user'); // défaut = 'user'

        // 2️⃣ Instanciation de l'entité et du formulaire correspondant
        switch ($userType) {
            case 'admin':
                $entity = new Admin();
                $form = $this->createForm(AdminRegistrationType::class, $entity);
                break;
            case 'producteur':
                $entity = new Producteur();
                $form = $this->createForm(ProducteurRegistrationType::class, $entity);
                break;
            case 'user':
            default:
                $entity = new User();
                $form = $this->createForm(UserRegistrationType::class, $entity);
        }

        $form->handleRequest($request);

        // 3️⃣ Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash du mot de passe
            $entity->setPassword(
                $passwordHasher->hashPassword($entity, $form->get('plainPassword')->getData())
            );

            // Upload du logo uniquement pour Producteur
            if ($entity instanceof Producteur) {
                $logoFile = $form->get('logo')->getData();
                if ($logoFile) {
                    $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                    try {
                        $logoFile->move(
                            $this->getParameter('logos_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors de l’upload du logo.');
                    }

                    $entity->setLogo($newFilename);
                }
            }

            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
            'userType' => $userType,
        ]);
    }
}