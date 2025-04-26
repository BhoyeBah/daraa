<?php

namespace App\Controller;

use App\Entity\Dahiras;
use App\Entity\Encadreur;
use App\Entity\Membres;
use App\Entity\Reunion;
use App\Entity\User;
use App\Repository\ReunionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    private $entityManager;
    private $reunionRepository;
    public function __construct(EntityManagerInterface $entityManager,ReunionRepository $reunionRepository)
    {
        $this->entityManager = $entityManager;
        $this->reunionRepository = $reunionRepository;

    }

 


    #[Route('/', name: 'app_home')]
    // #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request): Response
    {
        // Récupérer le mois et l'année depuis la requête (si présents)
        $mois = $request->query->get('mois', date('m'));  // Utilise le mois courant par défaut
        $annee = $request->query->get('annee', date('Y'));  // Utilise l'année courante par défaut
        $date = \DateTime::createFromFormat('Y-m', "$annee-$mois");
        // Récupérer le nombre de réunions par Dahira dans le mois spécifié
        $resultats = $this->reunionRepository->countReunionsParDahira((int)$mois, (int)$annee);
        // dd($resultats);
        // Compter les dahiras
          $membreCount = $this->entityManager->getRepository(Membres::class)->count([]);
          if($this->IsGranted('ROLE_USER')){
            $userCount = $this->entityManager->getRepository(User::class)->count(['roles' => 'ROLE_USER']);
          }
          // Compter les dahiras
          $dahiraCount = $this->entityManager->getRepository(Dahiras::class)->count([]);
          // Compter les encadreurs
          $encadreurCount = $this->entityManager->getRepository(Encadreur::class)->count([]);
          $reunionCount = $this->entityManager->getRepository(Reunion::class)->count([]);
            // Calculer le total des réunions pour ce mois
            $totalReunions = 0;
            foreach ($resultats as $resultat) {
                $totalReunions += (int)$resultat['nombre_reunions'];  // Additionner le nombre de réunions
            }
          $ratio = "$totalReunions/$dahiraCount";
            
          // Passer les comptages à la vue
          $data = [
              'userCount' => $userCount,
              'membreCount' => $membreCount,
              'dahiraCount' => $dahiraCount,
              'encadreurCount' => $encadreurCount,
              'reunionCount' => $reunionCount,
              'resultats' => $resultats,
              'mois' => $mois,
              'annee' => $annee,
              'ratio' => $ratio,
              'moisLettre' => $date->format('F'),
          ];

        if ($this->isGranted('ROLE_ADMIN')) {
            // dd("role admin");
            return $this->render('home/dashboard_admin.html.twig', $data);
        }
        
        if ($this->isGranted('ROLE_ENCADREUR')) {
            $user = $this->getUser();
            $encadreur = $user->getEncadreur();
            $dahira = $encadreur->getDahiras();
        
            $membreCount = $this->entityManager->getRepository(Membres::class)->count(['dahiras' => $dahira]);
            $reunionCount = $this->entityManager->getRepository(Reunion::class)->count(['dahiras' => $dahira]);
        
            // Préparer les données pour le rendu
            $dataDahira = [
                'dahira' => $dahira,
                'membreCount' => $membreCount,
                'reunionCount' => $reunionCount,
            ];
        
            return $this->render('home/dashboard_encadreur.html.twig', $dataDahira);
        }
        
        
        return $this->render('home/dashboard_admin.html.twig', $data);
    }
}
